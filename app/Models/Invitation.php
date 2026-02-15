<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'template_id',
        'unique_url',
        'status',
        'is_paid',
        'paid_at',
        'bride_name',
        'bride_father_name',
        'bride_mother_name',
        'groom_name',
        'groom_father_name',
        'groom_mother_name',
        'akad_date',
        'akad_time_start',
        'akad_time_end',
        'akad_location',
        'reception_date',
        'reception_time_start',
        'reception_time_end',
        'reception_location',
        'full_address',
        'latitude',
        'longitude',
        'google_maps_url',
        'music_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'akad_date' => 'date',
            'reception_date' => 'date',
            'is_paid' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the invitation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template used by the invitation.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the guests for the invitation.
     */
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the gallery photos for the invitation.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the views for the invitation.
     */
    public function views()
    {
        return $this->hasMany(InvitationView::class);
    }

    /**
     * Get the RSVPs for the invitation.
     */
    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    /**
     * Scope a query to only include published invitations.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
