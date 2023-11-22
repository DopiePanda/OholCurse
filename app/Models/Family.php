<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'families';
    protected $guarded = ['id'];

    public function life(): BelongsTo
    {
        return $this->belongsTo(LifeLog::class, 'character_id', 'character_id');
    }

    public function name(): HasOne
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'character_id');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }
}
