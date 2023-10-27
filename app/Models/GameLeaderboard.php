<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameLeaderboard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'multi_objects' => 'string',
    ];

    public function record()
    {
        return $this->hasOne(LeaderboardRecord::class, 'object_id', 'object_id')->orderBy('timestamp', 'desc');
    }

    public function object()
    {
        return $this->hasOne(GameObject::class, 'id', 'object_id');
    }

}
