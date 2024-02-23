<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserFriend extends Model
{
    protected $guarded = ['id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function reciever(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reciever_id', 'id');
    }

    public function chats_started(): HasMany
    {
        return $this->hasMany(UserConversation::class, 'sender_id', 'sender_id');
    }

    public function chats_joined(): HasMany
    {
        return $this->hasMany(UserConversation::class, 'reciever_id', 'sender_id');
    }
}
