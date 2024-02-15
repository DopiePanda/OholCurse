<?php

namespace App\Livewire\Conversations;

use Livewire\Component;
use Auth;
use DB;

use App\Models\PrivateMessage;
use App\Models\UserFriend;
use App\Models\UserConversation;

class Inbox extends Component
{
    public $conversations;
    public $conversation;

    public function mount()
    {
        $this->conversations = UserConversation::with('messages')
                            ->where('sender_id', Auth::user()->id)
                            ->orWhere('reciever_id', Auth::user()->id)
                            ->latest()
                            ->get();
        $this->openConversation(1);
    }

    public function render()
    {
        return view('livewire.conversations.inbox');
    }

    public function openConversation($id)
    {
        $this->conversation = UserConversation::with('messages')
                            ->where('id', $id)
                            ->first();
    }
}
