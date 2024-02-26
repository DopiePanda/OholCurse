<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function player()
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'hash');
    }

    public function report()
    {
        return $this->hasOne(Yumlog::class, 'player_hash', 'hash');
    }

    public function phex()
    {
        return $this->belongsTo(PhexHash::class, 'phex_hash', 'px_hash');
    }

    public function olgc()
    {
        return $this->belongsTo(PhexHash::class, 'phex_hash', 'olgc_hash');
    }
}
