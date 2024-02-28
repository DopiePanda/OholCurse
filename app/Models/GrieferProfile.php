<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrieferProfile extends Model
{
    protected $guarded = ["id"];

    public function group()
    {
        return $this->belongsTo(GrieferGroup::class, 'group_id', 'id');
    }

    public function curses()
    {
        return $this->hasMany(CurseLog::class, 'reciever_hash', 'player_hash');
    }

    public function curses_sent()
    {
        return $this->hasMany(CurseLog::class, 'player_hash', 'player_hash')->where('type', 'curse');
    }

    public function lives()
    {
        return $this->hasMany(LifeLog::class, 'player_hash', 'player_hash');
    }

    public function life()
    {
        return $this->hasOne(LifeLog::class, 'player_hash', 'player_hash')->where('type', 'death')->orderBy('id', 'desc');
    }

    public function profile()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }

    public function report()
    {
        return $this->hasOne(Yumlog::class, 'player_hash', 'player_hash');
    }
}
