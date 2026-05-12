<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GiftOrder;
use App\Models\Invitation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class GiftController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');
        MidtransConfig::$isSanitized  = config('services.midtrans.is_sanitized');
        MidtransConfig::$is3ds        = config('services.midtrans.is_3ds');

        if (!config('services.midtrans.is_production')) {
            MidtransConfig::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => [],
            ];
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLIC: List produk untuk undangan tertentu
    //  GET /api/public/invitations/{uniqueUrl}/gifts
    // ─────────────────────────────────────────────────────────────

    public function publicProducts(string $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        $products = Product::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($product) use ($invitation) {
                $soldCount = GiftOrder::where('product_id', $product->id)
                    ->where('invitation_id', $invitation->id)
                    ->where('status', 'paid')
                    ->count();

                $buyers = GiftOrder::where('product_id', $product->id)
                    ->where('invitation_id', $invitation->id)
                    ->where('status', 'paid')
                    ->orderBy('paid_at')
                    ->get(['buyer_name', 'buyer_message', 'paid_at'])
                    ->map(fn($o) => [
                        'name'    => $o->buyer_name,
                        'message' => $o->buyer_message,
                        'date'    => $o->paid_at?->toDateString(),
                    ]);

                $remaining = max(0, $product->stock - $soldCount);

                return [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'description' => $product->description,
                    'image_url'   => $product->image_path
                        ? asset('storage/' . $product->image_path)
                        : null,
                    'price'       => $product->price,
                    'stock'       => $product->stock,
                    'sold'        => $soldCount,
                    'remaining'   => $remaining,
                    'is_available'=> $remaining > 0,
                    'buyers'      => $buyers,
                ];
            });

        return response()->json([
            'success'  => true,
            'products' => $products,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLIC: Buat order hadiah (tamu tanpa akun)
    //  POST /api/public/invitations/{uniqueUrl}/gifts/order
    // ─────────────────────────────────────────────────────────────

    public function publicOrder(Request $request, string $uniqueUrl)
    {
        $invitation = Invitation::where('unique_url', $uniqueUrl)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'product_id'    => 'required|integer|exists:products,id',
            'buyer_name'    => 'required|string|max:255',
            'buyer_email'   => 'required|email|max:255',
            'buyer_phone'   => 'nullable|string|max:20',
            'buyer_message' => 'nullable|string|max:500',
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        // Cek stok
        $soldCount = GiftOrder::where('product_id', $product->id)
            ->where('invitation_id', $invitation->id)
            ->where('status', 'paid')
            ->count();

        if ($soldCount >= $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Produk ini sudah habis dipesan.',
            ], 422);
        }

        // Cek apakah email ini sudah order produk yang sama untuk undangan ini
        $existing = GiftOrder::where('product_id', $product->id)
            ->where('invitation_id', $invitation->id)
            ->where('buyer_email', $validated['buyer_email'])
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if ($existing && $existing->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan hadiah ini.',
            ], 422);
        }

        // Reuse pending order jika ada
        if ($existing && $existing->snap_token) {
            return response()->json([
                'success'    => true,
                'order_code' => $existing->order_code,
                'snap_token' => $existing->snap_token,
                'amount'     => $existing->amount,
                'is_production' => config('services.midtrans.is_production'),
            ]);
        }

        $orderCode = 'GIFT-' . $invitation->id . '-' . $product->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderCode,
                'gross_amount' => $product->price,
            ],
            'customer_details' => [
                'first_name' => $validated['buyer_name'],
                'email'      => $validated['buyer_email'],
                'phone'      => $validated['buyer_phone'] ?? '',
            ],
            'item_details' => [
                [
                    'id'       => 'GIFT-' . $product->id,
                    'price'    => $product->price,
                    'quantity' => 1,
                    'name'     => 'Hadiah: ' . $product->name . ' untuk ' . $invitation->bride_name . ' & ' . $invitation->groom_name,
                ],
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/gift/finish',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $order = GiftOrder::create([
                'invitation_id'   => $invitation->id,
                'product_id'      => $product->id,
                'buyer_name'      => $validated['buyer_name'],
                'buyer_email'     => $validated['buyer_email'],
                'buyer_phone'     => $validated['buyer_phone'] ?? null,
                'buyer_message'   => $validated['buyer_message'] ?? null,
                'order_code'      => $orderCode,
                'snap_token'      => $snapToken,
                'amount'          => $product->price,
                'status'          => 'pending',
                'shipping_address'=> $invitation->full_address,
            ]);

            return response()->json([
                'success'       => true,
                'order_code'    => $orderCode,
                'snap_token'    => $snapToken,
                'amount'        => $product->price,
                'is_production' => config('services.midtrans.is_production'),
            ]);
        } catch (\Exception $e) {
            Log::error('Gift order Midtrans error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi. Coba lagi.',
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLIC: Cek status order
    //  GET /api/public/gift-orders/{orderCode}/status
    // ─────────────────────────────────────────────────────────────

    public function publicOrderStatus(string $orderCode)
    {
        $order = GiftOrder::where('order_code', $orderCode)
            ->with('product:id,name,image_path')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order'   => [
                'order_code'   => $order->order_code,
                'status'       => $order->status,
                'product_name' => $order->product->name,
                'amount'       => $order->amount,
                'buyer_name'   => $order->buyer_name,
                'paid_at'      => $order->paid_at?->toIso8601String(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  MIDTRANS WEBHOOK
    //  POST /api/gift/notification
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

            Log::info('Gift notification', [
                'order_id' => $orderId,
                'status'   => $transactionStatus,
            ]);

            $order = GiftOrder::where('order_code', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $newStatus = $this->resolveStatus($transactionStatus, $fraudStatus);

            $order->update([
                'transaction_id'    => $transactionId,
                'payment_type'      => $paymentType,
                'status'            => $newStatus,
                'midtrans_response' => $request->all(),
                'paid_at'           => $newStatus === 'paid' ? now() : null,
            ]);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Gift notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  AUTHENTICATED: List gift orders untuk undangan (pengantin)
    //  GET /api/invitations/{id}/gifts
    // ─────────────────────────────────────────────────────────────

    public function invitationGifts(Request $request, int $invitationId)
    {
        $invitation = Invitation::where('user_id', $request->user()->id)
            ->findOrFail($invitationId);

        $orders = GiftOrder::where('invitation_id', $invitation->id)
            ->with('product:id,name,image_path,price')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($o) => [
                'id'              => $o->id,
                'order_code'      => $o->order_code,
                'product_name'    => $o->product->name,
                'product_image'   => $o->product->image_path ? asset('storage/' . $o->product->image_path) : null,
                'buyer_name'      => $o->buyer_name,
                'buyer_email'     => $o->buyer_email,
                'buyer_phone'     => $o->buyer_phone,
                'buyer_message'   => $o->buyer_message,
                'amount'          => $o->amount,
                'status'          => $o->status,
                'payment_type'    => $o->payment_type,
                'paid_at'         => $o->paid_at?->toIso8601String(),
                'shipping_status' => $o->shipping_status,
                'tracking_number' => $o->tracking_number,
                'shipped_at'      => $o->shipped_at?->toIso8601String(),
                'delivered_at'    => $o->delivered_at?->toIso8601String(),
            ]);

        $totalRevenue = GiftOrder::where('invitation_id', $invitation->id)
            ->where('status', 'paid')
            ->sum('amount');

        return response()->json([
            'success'       => true,
            'orders'        => $orders,
            'total_revenue' => $totalRevenue,
            'paid_count'    => $orders->where('status', 'paid')->count(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────

    private function resolveStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'paid';
        }

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'pending'    => 'pending',
            'deny', 'cancel', 'failure' => 'cancelled',
            'expire'     => 'expired',
            default      => 'pending',
        };
    }
}
