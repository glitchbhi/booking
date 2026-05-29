<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'ground_id',
        'slot_date',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('slot_date', $date);
    }

    public function scopeForGround($query, $groundId)
    {
        return $query->where('ground_id', $groundId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('slot_date', '>=', now()->toDateString());
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('slot_date', [$startDate, $endDate]);
    }
}
