<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaComment extends Model
{
    use HasFactory;

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }
}
