<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameListItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'game_list_id',
        'game_id',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
        'game_id' => 'integer',
    ];

    public function gameList(): BelongsTo
    {
        return $this->belongsTo(GameList::class);
    }
}
