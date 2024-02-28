<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrieferGroup extends Model
{
    protected $guarded = ["id"];

    public function profiles()
    {
        return $this->hasMany(GrieferProfile::class, 'group_id', 'id');
    }
}
