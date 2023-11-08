<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'multi_objects' => 'string',
    ];

    public function character()
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id');
    }

    public function player()
    {
        return $this->belongsTo(Leaderboard::class, 'leaderboard_id', 'leaderboard_id');
    }

    public function leaderboard()
    {
        return $this->belongsTo(GameLeaderboard::class, 'game_leaderboard_id', 'id');
    }

    public function lifeName()
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function playerName()
    {
        return $this->hasOne(Leaderboard::class, 'leaderboard_id', 'leaderboard_id');
    }


}
