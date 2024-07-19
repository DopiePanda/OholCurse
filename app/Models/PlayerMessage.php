<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerMessage extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'items' => 'json'
    ];
}
