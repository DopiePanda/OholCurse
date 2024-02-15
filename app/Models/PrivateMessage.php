<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateMessage extends Model
{
    use HasFactory;

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(UserConversation::class, 'conversation_id', 'id');
    }
}
