<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileBadge extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Leaderboard::class, 'player_hash', 'player_hash');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'badge_id', 'id');
    }
}
