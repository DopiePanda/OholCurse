<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhexHash extends Model
{
    use HasFactory;

    protected $table = 'phex_hashes';

    protected $guarded = ['id'];
}
