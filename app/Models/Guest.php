<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * Guest category constants.
     */
    const CATEGORY_FAMILY = 'family';
    const CATEGORY_FRIEND = 'friend';
    const CATEGORY_COLLEAGUE = 'colleague';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invitation_id',
        'name',
        'category',
        'phone',
        'whatsapp_number',
        'email',
        'address',
        'pax',
        'qr_token',
        'checked_in_at',
        'souvenir_taken_at',
        'checked_out_at',
        'souvenir2_taken_at',
    ];

    protected $casts = [
        'checked_in_at'      => 'datetime',
        'souvenir_taken_at'  => 'datetime',
        'checked_out_at'     => 'datetime',
        'souvenir2_taken_at' => 'datetime',
        'pax'                => 'integer',
    ];

    /**
     * Get the invitation that owns the guest.
     */
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
