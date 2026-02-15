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
        'whatsapp_number',
    ];

    /**
     * Get the invitation that owns the guest.
     */
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
