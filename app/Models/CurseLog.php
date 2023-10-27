<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CurseLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function life(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id')->orderBy('timestamp', 'desc');
    }

    public function reciever()
    {
        return $this->belongsTo(LifeLog::class, 'reciever_hash', 'player_hash')->orderBy('timestamp', 'desc');
    }

    public function name()
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'reciever_hash');
    }

    public function leaderboard_recieved()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash')->orderBy('leaderboard_id', 'desc');
    }

    public function scores()
    {
        return $this->hasOne(PlayerScore::class, 'player_hash', 'reciever_hash');
    }

    public function scores_recieved()
    {
        return $this->hasOne(PlayerScore::class, 'player_hash', 'player_hash');
    }
}