<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurseLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function life(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id')->orderBy('timestamp', 'desc');
    }

    public function newest_death(): HasOne
    {
        return $this->hasOne(LifeLog::class, 'player_hash', 'player_hash')->where('type', 'death')->orderBy('timestamp', 'desc');
    }

    public function forgives(): HasMany
    {
        return $this->hasMany(CurseLog::class, 'player_hash', 'player_hash')->where('type', 'forgive')->orderBy('timestamp', 'desc');
    }

    public function reciever()
    {
        return $this->belongsTo(LifeLog::class, 'reciever_hash', 'player_hash')->orderBy('timestamp', 'desc');
    }

    public function name()
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function name_recieved()
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function contact()
    {
        return $this->hasOne(UserContact::class, 'hash', 'reciever_hash')->where('user_id', Auth::user()->id ?? null);      
    }

    public function contact_recieved()
    {
        return $this->hasOne(UserContact::class, 'hash', 'player_hash')->where('user_id', Auth::user()->id ?? null);      
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

    public function griefer_profile()
    {
        return $this->hasOne(GrieferProfile::class, 'player_hash', 'reciever_hash');
    }

    public function griefer_profile_recieved()
    {
        return $this->hasOne(GrieferProfile::class, 'player_hash', 'player_hash');
    }
}