<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'rating',
        'liked',
        'review',
        'reviewed_at',
    ];

    protected $casts = [
        'liked' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($rating) {
            if (!isset($rating->rating) || $rating->rating < 0.5 || $rating->rating > 5 || $rating->rating * 2 != (int)($rating->rating * 2)) {
                throw new \Exception('Rating must be between 0.5 and 5.0 and in increments of 0.5.');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
