<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhexHash extends Model
{
    use HasFactory;

    protected $table = 'phex_hashes';

    protected $guarded = ['id'];

    public function phex(): HasOne
    {
        return $this->hasOne(UserContact::class, 'phex_hash', 'px_hash')->limit(1);
    }

    public function olgc(): HasOne
    {
        return $this->hasOne(UserContact::class, 'phex_hash', 'olgc_hash');
    }

    public function player(): HasOne
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }
}
