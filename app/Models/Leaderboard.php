<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Leaderboard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function score(): HasOne
    {
        return $this->hasOne(PlayerScore::class, 'leaderboard_id', 'leaderboard_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(LeaderboardRecord::class, 'leaderboard_id', 'leaderboard_id');
    }

    public function contact()
    {
        return $this->hasOne(UserContact::class, 'hash', 'player_hash')->where('user_id', Auth::user()->id ?? null);
    }

    
}
