<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

use App\Models\UserConversation;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
Broadcast::channel('conversations.{conversationId}', function (User $user, int $conversationId) 
{
    $conversation = UserConversation::findOrNew($conversationId);
    Log::debug('Event broadcasted');

    if($user->id  == $conversation->sender_id)
    {
        Log::debug('Return sender id');
        return ['ably-capability' => ["subscribe", "history"]];
    }

    if($user->id  == $conversation->reciever_id)
    {
        Log::debug('Return reciever id');
        return ['ably-capability' => ["subscribe", "history"]];
    }
    
    return false;
});

Broadcast::channel('message.{conversation}', 'App\Broadcasting\Message');
*/