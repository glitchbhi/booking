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
}
