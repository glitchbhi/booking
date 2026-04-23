<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemRating extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
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

    // Get average system rating
    public static function getAverageRating()
    {
        return static::avg('rating') ?? 0;
    }

    // Get total system ratings count
    public static function getTotalCount()
    {
        return static::count();
    }

    // Get rating distribution
    public static function getRatingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = static::where('rating', $i)->count();
        }
        return $distribution;
    }

    // Get rating percentage
    public static function getRatingPercentage($rating)
    {
        $total = static::getTotalCount();
        if ($total === 0) {
            return 0;
        }
        $count = static::where('rating', $rating)->count();
        return round(($count / $total) * 100, 2);
    }

    // Check if user has rated
    public static function hasUserRated($userId)
    {
        return static::where('user_id', $userId)->exists();
    }

    // Get user's rating
    public static function getUserRating($userId)
    {
        return static::where('user_id', $userId)->first();
    }
}
