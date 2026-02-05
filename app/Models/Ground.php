<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ground extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'sport_type_id',
        'name',
        'description',
        'location',
        'address',
        'phone',
        'latitude',
        'longitude',
        'rate_per_hour',
        'night_rate_per_hour',
        'capacity',
        'capacity_description',
        'day_rate_start',
        'day_rate_end',
        'night_rate_start',
        'night_rate_end',
        'images',
        'is_active',
        'average_rating',
        'total_bookings',
        'total_reviews',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'rate_per_hour' => 'decimal:2',
        'night_rate_per_hour' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'day_rate_start' => 'datetime:H:i',
        'day_rate_end' => 'datetime:H:i',
        'night_rate_start' => 'datetime:H:i',
        'night_rate_end' => 'datetime:H:i',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sportType()
    {
        return $this->belongsTo(SportsType::class, 'sport_type_id');
    }

    public function availabilities()
    {
        return $this->hasMany(GroundAvailability::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTrending($query, $days = 7)
    {
        return $query->withCount([
            'bookings' => function ($q) use ($days) {
                $q->where('created_at', '>=', now()->subDays($days))
                  ->whereIn('status', ['booked', 'ongoing', 'completed']);
            }
        ])->orderBy('bookings_count', 'desc');
    }

    public function scopeBest($query)
    {
        return $query->where('average_rating', '>=', 4)
                     ->where('total_bookings', '>=', 5)
                     ->orderBy('average_rating', 'desc')
                     ->orderBy('total_bookings', 'desc');
    }

    // Methods
    public function updateRating()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }

    public function incrementBookingCount()
    {
        $this->increment('total_bookings');
    }
}
