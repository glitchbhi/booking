<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ground_name',
        'license_number',
        'category',
        'team_size',
        'day_time_start',
        'price_day',
        'price_night',
        'night_time_start',
        'available_at_night',
        'ground_images',
        'business_address',
        'contact_number',
        'opening_time',
        'closing_time',
        'facilities',
        'reason',
        'business_details',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'available_at_night' => 'boolean',
        'ground_images' => 'array',
        'price_day' => 'decimal:2',
        'price_night' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
