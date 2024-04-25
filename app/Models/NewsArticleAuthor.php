<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsArticleAuthor extends Model
{
    protected $guarded = ['id'];

    public function article()
    {
        return $this->belongsTo(NewsArticle::class, 'article_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->with('roles');
    }
}
