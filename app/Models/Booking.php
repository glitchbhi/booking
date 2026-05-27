<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'ground_id',
        'start_time',
        'end_time',
        'duration_hours',
        'rate_per_hour',
        'total_amount',
        'status',
        'booking_type',
        'payment_proof',
        'expires_at',
        'cancellation_reason',
        'cancelled_at',
        'is_no_show',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'decimal:2',
        'rate_per_hour' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_no_show' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'TB-' . strtoupper(Str::random(10));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['booked', 'ongoing']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeOnline($query)
    {
        return $query->where('booking_type', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('booking_type', 'offline');
    }

    // Status helpers
    public function isBooked()
    {
        return $this->status === 'booked';
    }

    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        if ($this->status !== 'booked') {
            return false;
        }

        return now()->diffInHours($this->start_time, false) >= 4;
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && !$this->review;
    }

    // New payment workflow methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaymentSubmitted()
    {
        return $this->status === 'payment_submitted';
    }

    public function isWaitingApproval()
    {
        return $this->status === 'waiting_approval';
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }

    public function isExpiredTime()
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    public function canUploadPayment()
    {
        return $this->status === 'pending' && !$this->isExpiredTime();
    }

    public function getRemainingTime()
    {
        if (!$this->expires_at) return null;
        
        $remaining = $this->expires_at->diffInSeconds(now());
        
        if ($remaining <= 0) {
            return 'Expired';
        }
        
        $minutes = floor($remaining / 60);
        $seconds = $remaining % 60;
        
        return "{$minutes}m {$seconds}s";
    }

    public function markAsPending()
    {
        $this->status = 'pending';
        $this->expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function uploadPaymentProof($filePath)
    {
        $this->payment_proof = $filePath;
        $this->status = 'payment_submitted';
        $this->save();
    }

    public function markAsWaitingApproval()
    {
        $this->status = 'waiting_approval';
        $this->save();
    }

    public function confirmBooking()
    {
        $this->status = 'booked';
        $this->save();
    }

    public function expireBooking()
    {
        $this->status = 'expired';
        $this->save();
    }
}
