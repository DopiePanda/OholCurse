<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameObject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mapEntries(): BelongsTo
    {
        return $this->belongsTo(MapLog::class, 'id', 'object_id');
    }
}
