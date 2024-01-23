<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Auth;
use Carbon\Carbon;

class Yumlog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id');
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }

    public function curses(): HasMany
    {
        return $this->hasMany(CurseLog::class, 'reciever_hash', 'player_hash')->where('type', 'curse');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->setTimezone(Auth::user()->timezone)->format('Y-m-d h:i:s');
    }
}
