<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function character(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id');
    }

    public function birth(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id')->where('type', 'birth');
    }

    public function name(): HasOne
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function object(): HasOne
    {
        return $this->hasOne(GameObject::class, 'id', 'object_id');
    }
}
