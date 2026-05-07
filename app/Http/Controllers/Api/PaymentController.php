<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');
        MidtransConfig::$isSanitized  = config('services.midtrans.is_sanitized');
        MidtransConfig::$is3ds        = config('services.midtrans.is_3ds');

        // Disable SSL verification in local/development environment
        // (Windows dev machines often lack CA certificates)
        // Must include CURLOPT_HTTPHEADER to avoid SDK bug with array key access
        if (!config('services.midtrans.is_production')) {
            MidtransConfig::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => [],
            ];
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  CREATE SNAP TOKEN
    //  POST /api/invitations/{id}/payment/create
    // ─────────────────────────────────────────────────────────────

    public function create(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        // Already paid — no need to pay again
        if ($invitation->is_paid) {
            return response()->json([
                'success'    => false,
                'already_paid' => true,
                'message'    => 'Undangan ini sudah dibayar.',
            ], 409);
        }

        $price  = config('services.midtrans.price', 50000);
        $user   = $request->user();

        // Reuse pending payment if exists (avoid duplicate Snap tokens)
        $payment = Payment::where('invitation_id', $invitation->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($payment && $payment->snap_token) {
            return response()->json([
                'success'    => true,
                'snap_token' => $payment->snap_token,
                'order_id'   => $payment->order_id,
                'amount'     => $payment->amount,
                'client_key' => config('services.midtrans.client_key'),
                'is_production' => config('services.midtrans.is_production'),
            ]);
        }

        // Create new order
        $orderId = 'INV-' . $invitation->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => [
                [
                    'id'       => 'UNDANGAN-' . $invitation->id,
                    'price'    => $price,
                    'quantity' => 1,
                    'name'     => 'Undangan Digital — ' . $invitation->bride_name . ' & ' . $invitation->groom_name,
                ],
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/payment/finish',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $payment = Payment::create([
                'invitation_id' => $invitation->id,
                'user_id'       => $user->id,
                'order_id'      => $orderId,
                'snap_token'    => $snapToken,
                'amount'        => $price,
                'status'        => 'pending',
            ]);

            return response()->json([
                'success'       => true,
                'snap_token'    => $snapToken,
                'order_id'      => $orderId,
                'amount'        => $price,
                'client_key'    => config('services.midtrans.client_key'),
                'is_production' => config('services.midtrans.is_production'),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans create token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran. Coba lagi.',
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  CHECK PAYMENT STATUS
    //  GET /api/invitations/{id}/payment/status
    // ─────────────────────────────────────────────────────────────

    public function status(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        // Get latest payment record
        $payment = Payment::where('invitation_id', $invitation->id)
            ->latest()
            ->first();

        $paymentStatus = $payment ? $payment->status : null;
        $isPaid = $invitation->is_paid ||
                  ($payment && in_array($payment->status, ['success', 'paid', 'completed']));

        return response()->json([
            'success'        => true,
            'is_paid'        => $isPaid,
            'paid_at'        => $invitation->paid_at?->toIso8601String(),
            'amount'         => config('services.midtrans.price', 50000),
            'payment_status' => $paymentStatus,
            'payment_type'   => $payment?->payment_type,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  MIDTRANS WEBHOOK (Notification)
    //  POST /api/payment/notification  (public, no auth)
    // ─────────────────────────────────────────────────────────────

    public function notification(Request $request)
    {
        try {
            $notif = new Notification();

            $orderId           = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;
            $transactionId     = $notif->transaction_id;
            $paymentType       = $notif->payment_type;

            Log::info('Midtrans notification', [
                'order_id' => $orderId,
                'status'   => $transactionStatus,
                'fraud'    => $fraudStatus,
            ]);

            $payment = Payment::where('order_id', $orderId)->first();
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Determine final status
            $newStatus = $this->resolveStatus($transactionStatus, $fraudStatus);

            $payment->update([
                'transaction_id'    => $transactionId,
                'payment_type'      => $paymentType,
                'status'            => $newStatus,
                'midtrans_response' => $request->all(),
                'paid_at'           => $newStatus === 'success' ? now() : null,
            ]);

            // Mark invitation as paid
            if ($newStatus === 'success') {
                $payment->invitation->update([
                    'is_paid' => true,
                    'paid_at' => now(),
                    'status'  => 'published', // auto-publish after payment
                ]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────

    private function resolveStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'success';
        }

        return match ($transactionStatus) {
            'settlement' => 'success',
            'pending'    => 'pending',
            'deny', 'cancel', 'failure' => 'failed',
            'expire'     => 'expired',
            default      => 'pending',
        };
    }
}
