<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LifeLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function name(): HasOne
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function curses(): HasMany
    {
        return $this->hasMany(CurseLog::class, 'character_id', 'character_id');
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }
}
