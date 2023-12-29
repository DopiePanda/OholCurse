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
        return $this->belongsTo(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function playerName()
    {
        return $this->hasOne(Leaderboard::class, 'leaderboard_id', 'leaderboard_id');
    }

    public function currentRecord()
    {
        return $this->hasOne(LeaderboardRecord::class, 'game_leaderboard_id', 'game_leaderboard_id')->where('ghost', 0)->orderBy('amount', 'desc');
    }

    public function currentGhostRecord()
    {
        return $this->hasOne(LeaderboardRecord::class, 'game_leaderboard_id', 'game_leaderboard_id')->where('ghost', 1)->orderBy('amount', 'desc');
    }


}
