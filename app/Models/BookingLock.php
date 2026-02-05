<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ground_id',
        'user_id',
        'start_time',
        'end_time',
        'locked_until',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'locked_until' => 'datetime',
    ];

    // Relationships
    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('locked_until', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('locked_until', '<=', now());
    }

    // Helpers
    public function isExpired()
    {
        return now()->greaterThan($this->locked_until);
    }
}
