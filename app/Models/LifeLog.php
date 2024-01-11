<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class LifeLog extends Model
{
    use HasFactory;
    

    protected $guarded = ['id'];

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function getLocalKeyName()
    {
        return 'character_id';
    }

    public function name(): HasOne
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function life_name(): HasOne
    {
        return $this->hasOne(LifeNameLog::class, 'character_id', 'character_id');
    }

    public function curses(): HasMany
    {
        return $this->hasMany(CurseLog::class, 'character_id', 'character_id');
    }

    public function foods(): HasMany
    {
        return $this->hasMany(FoodLog::class, 'character_id', 'character_id');
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'character_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'character_id')->select('character_id', 'parent_id')->with('children:character_id,parent_id', 'name:character_id,name');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }

    public function getFamilyAttribute()
    {
        return !$this->parent_id ? $this->children->prepend($this) : $this->parent->children->prepend($this->parent);
    }
}
