<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'product_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_message',
        'order_code',
        'snap_token',
        'transaction_id',
        'amount',
        'status',
        'payment_type',
        'midtrans_response',
        'paid_at',
        'shipping_address',
        'shipping_status',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at'           => 'datetime',
        'shipped_at'        => 'datetime',
        'delivered_at'      => 'datetime',
        'amount'            => 'integer',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
