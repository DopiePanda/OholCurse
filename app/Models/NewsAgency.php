<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsAgency extends Model
{

    protected $guarded = ['id'];

    public function articles() 
    {
        return $this->hasMany(NewsArticle::class, 'agency', 'name');
    }
}
