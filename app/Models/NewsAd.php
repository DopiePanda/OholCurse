<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsAd extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
