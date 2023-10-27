<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerScore extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Leaderboard::class, 'leaderboard_id', 'leaderboard_id');
    }
}
