<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsArticle extends Model
{
    protected $guarded = ['id'];

    public function authors()
    {
        return $this->hasMany(NewsArticleAuthor::class, 'id', 'article_id');
    }

    public function image($position)
    {
        return $this->hasOne(NewsArticleImage::class, 'article_id', 'id')->where('position', $position);    
    }

    public function images()
    {
        return $this->hasMany(NewsArticleImage::class, 'article_id', 'id');
    }
}
