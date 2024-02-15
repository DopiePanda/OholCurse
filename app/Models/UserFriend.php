<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFriend extends Model
{
    use HasFactory;

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function reciever(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reciever_id', 'id');
    }
}
