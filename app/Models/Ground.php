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
        'license_number',
        'description',
        'owner_motivation',
        'location',
        'address',
        'phone',
        'latitude',
        'longitude',
        'rate_per_hour',
        'night_rate_per_hour',
        'capacity',
        'team_size',
        'capacity_description',
        'day_rate_start',
        'day_rate_end',
        'night_rate_start',
        'night_rate_end',
        'images',
        'is_active',
        'is_under_maintenance',
        'maintenance_start_date',
        'maintenance_end_date',
        'maintenance_reason',
        'average_rating',
        'total_bookings',
        'total_reviews',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_under_maintenance' => 'boolean',
        'maintenance_start_date' => 'datetime',
        'maintenance_end_date' => 'datetime',
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

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('is_under_maintenance', false)
              ->orWhere(function ($subQuery) {
                  $subQuery->whereNull('maintenance_start_date')
                           ->orWhere('maintenance_start_date', '>', now());
              });
        })->where(function ($q) {
            $q->whereNull('maintenance_end_date')
              ->orWhere('maintenance_end_date', '<=', now())
              ->orWhere('is_under_maintenance', false);
        });
    }

    /**
     * Scope to get grounds that are currently available for booking
     * (excludes grounds under maintenance right now)
     */
    public function scopeCurrentlyAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('is_under_maintenance', false)
              ->orWhere(function ($subQuery) {
                  $subQuery->whereNull('maintenance_start_date')
                           ->orWhere('maintenance_start_date', '>', now());
              });
        })->where(function ($q) {
            $q->whereNull('maintenance_end_date')
              ->orWhere('maintenance_end_date', '<=', now());
        });
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

    /**
     * Check if ground is currently under maintenance based on schedule
     */
    public function isUnderMaintenanceSchedule(): bool
    {
        if (!$this->maintenance_start_date || !$this->maintenance_end_date) {
            return false;
        }

        $now = now();
        return $now->isBetween($this->maintenance_start_date, $this->maintenance_end_date);
    }

    /**
     * Check if maintenance period has expired
     */
    public function isMaintenanceExpired(): bool
    {
        if (!$this->maintenance_end_date) {
            return false;
        }

        return now()->isAfter($this->maintenance_end_date);
    }

    /**
     * Automatically end maintenance if schedule has passed
     */
    public function checkAndEndMaintenance(): bool
    {
        if ($this->isMaintenanceExpired() && $this->is_under_maintenance) {
            $this->update([
                'is_under_maintenance' => false,
                'maintenance_start_date' => null,
                'maintenance_end_date' => null,
                'maintenance_reason' => null,
            ]);
            return true;
        }
        return false;
    }

    /**
     * Get remaining maintenance time
     */
    public function getMaintenanceRemainingTime()
    {
        if (!$this->maintenance_end_date) {
            return null;
        }

        if ($this->isMaintenanceExpired()) {
            return null;
        }

        return $this->maintenance_end_date->diffForHumans();
    }

    public function incrementBookingCount()
    {
        $this->increment('total_bookings');
    }
}
