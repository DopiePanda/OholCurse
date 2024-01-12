<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapLog extends Model
{
    use HasFactory;

    public function objects(): HasMany
    {
        return $this->hasMany(GameObject::class, 'id', 'object_id');
    }

    public function object(): HasOne
    {
        return $this->hasOne(GameObject::class, 'id', 'object_id');
    }

    public function name(): BelongsTo
    {
        return $this->belongsTo(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function life(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id');
    }

    public function death(): HasOne
    {
        return $this->hasOne(LifeLog::class, 'character_id', 'character_id')->where('type', 'death');
    }

    public function lives(): HasMany
    {
        return $this->hasMany(LifeLog::class, 'character_id', 'character_id')->orderBy('type', 'asc');
    }
}
