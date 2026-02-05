<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ground_id',
        'booking_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->ground->updateRating();
        });

        static::updated(function ($review) {
            $review->ground->updateRating();
        });

        static::deleted(function ($review) {
            $review->ground->updateRating();
        });
    }
}
