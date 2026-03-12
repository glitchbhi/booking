<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'owner_status',
        'is_suspended',
        'suspended_until',
        'late_cancel_count',
        'google_id',
        'avatar',
        'password_set_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'is_suspended' => 'boolean',
            'password_set_at' => 'datetime',
        ];
    }

    // Relationships
    public function grounds()
    {
        return $this->hasMany(Ground::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function systemRating()
    {
        return $this->hasOne(SystemRating::class);
    }

    public function ownerRequest()
    {
        return $this->hasOne(OwnerRequest::class)->latestOfMany();
    }

    // Role helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner' && $this->owner_status === 'approved';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function canBook()
    {
        return !$this->is_suspended || ($this->suspended_until && now()->greaterThan($this->suspended_until));
    }

    /**
     * Check if user signed up with Google OAuth
     */
    public function isGoogleUser()
    {
        return !empty($this->google_id);
    }

    /**
     * Check if Google user has set their own password
     */
    public function hasSetPassword()
    {
        // If not a Google user, they always have their password
        if (!$this->isGoogleUser()) {
            return true;
        }
        
        // Check if password_set_at is set (will add this field)
        return !empty($this->password_set_at);
    }
}
