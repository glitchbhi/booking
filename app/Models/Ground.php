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
        'bank_name',
        'account_number',
        'latitude',
        'longitude',
        'rate_per_hour',
        'night_rate_per_hour',
        'available_at_night',
        'capacity',
        'team_size',
        'capacity_description',
        'day_rate_start',
        'day_rate_end',
        'night_rate_start',
        'night_rate_end',
        'opening_time',
        'closing_time',
        'slot_duration',
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
        'available_at_night' => 'boolean',
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
        'opening_time' => 'datetime:H:i:s',
        'closing_time' => 'datetime:H:i:s',
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

    public function slots()
    {
        return $this->hasMany(GroundSlot::class);
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

    /**
     * Get available slots for a specific date
     */
    public function getAvailableSlotsForDate($date)
    {
        return $this->slots()
            ->where('slot_date', $date)
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get all slots for a specific date (available and booked)
     */
    public function getSlotsForDate($date)
    {
        return $this->slots()
            ->where('slot_date', $date)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get available slots for a date range
     */
    public function getAvailableSlotsForDateRange($startDate, $endDate)
    {
        return $this->slots()
            ->whereBetween('slot_date', [$startDate, $endDate])
            ->where('is_available', true)
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Check if ground has any available slots for a date
     */
    public function hasAvailableSlots($date): bool
    {
        return $this->slots()
            ->where('slot_date', $date)
            ->where('is_available', true)
            ->exists();
    }

    /**
     * Get the ground's current schedule information
     * Useful for verifying slot synchronization
     */
    public function getScheduleInfo(): array
    {
        return [
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'slot_duration' => $this->slot_duration ?? 60,
            'day_rate_start' => $this->day_rate_start,
            'day_rate_end' => $this->day_rate_end,
            'night_rate_start' => $this->night_rate_start,
            'night_rate_end' => $this->night_rate_end,
            'rate_per_hour' => $this->rate_per_hour,
            'night_rate_per_hour' => $this->night_rate_per_hour,
            'is_active' => $this->is_active,
            'is_under_maintenance' => $this->is_under_maintenance,
            'total_slots' => $this->slots()->count(),
            'available_slots' => $this->slots()->where('is_available', true)->count(),
            'booked_slots' => $this->slots()->where('is_available', false)->count(),
        ];
    }

    /**
     * Verify that database and model are synchronized
     * Returns true if all fields match current database values
     */
    public function isSynchronized(): bool
    {
        // Reload from database
        $fresh = self::find($this->id);
        
        if (!$fresh) {
            return false;
        }

        // Compare key fields
        return $this->opening_time === $fresh->opening_time &&
               $this->closing_time === $fresh->closing_time &&
               $this->slot_duration === $fresh->slot_duration &&
               $this->rate_per_hour === $fresh->rate_per_hour &&
               $this->night_rate_per_hour === $fresh->night_rate_per_hour &&
               $this->day_rate_start === $fresh->day_rate_start &&
               $this->day_rate_end === $fresh->day_rate_end &&
               $this->night_rate_start === $fresh->night_rate_start &&
               $this->night_rate_end === $fresh->night_rate_end;
    }
}
