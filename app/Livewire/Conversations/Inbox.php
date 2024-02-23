<?php

namespace App\Livewire\Conversations;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;
use Log;

use App\Models\PrivateMessage;
use App\Models\UserFriend;
use App\Models\UserConversation;

use App\Events\PrivateMessageSent;

class Inbox extends Component
{
    public $user;
    public $show_chat;
    public $tab;

    public $search;
    public $conversations;
    public $selected_conversation;
    public $message;

    public $friend_requests;
    public $friends;
    public $selected_friend;

    public $show_load_time = 0;
    public $time_start;

    public function mount()
    {
        $this->time_start = microtime(true);
        $this->user = Auth::user();
        $this->show_chat = false;
        $this->tab = 'inbox';

        $this->selected_conversation = null;
        $this->getAllConversations();

        $this->getCountIncomingFriendRequests();
    }

    public function render()
    {
        return view('livewire.conversations.inbox');
    }

    public function toggleChat()
    {
        $this->time_start = microtime(true);
        if($this->show_chat == false)
        {
            $this->show_chat = true;
        }
        else
        {
            $this->selected_conversation = null;
            $this->show_chat = false;
            $this->tab = 'inbox';
        }
    }

    public function openChat()
    {
        $this->show_chat = true;
    }

    public function closeChat()
    {
        $this->show_chat = false;
    }

    public function searchConversations()
    {
        $conversations = UserConversation::select('id')
                ->where(function($query) {
                    $query->where('sender_id', $this->user->id)
                    ->orWhere('reciever_id', $this->user->id);
                })
                ->get()
                ->toArray();

        $ids = PrivateMessage::select('conversation_id')
                ->where('message', 'like', '%'.$this->search.'%')
                ->whereIn('conversation_id', $conversations)
                ->get()
                ->toArray();

        $this->conversations = UserConversation::with('messages')
                ->whereIn('id', $ids)
                ->latest()
                ->get();
    }

    public function newConversation()
    {
        $this->time_start = microtime(true);
        $this->friends = UserFriend::with('reciever:id,username')
                            ->where('sender_id', $this->user->id)
                            ->where('status', 2)
                            ->orderBy('updated_at', 'desc')
                            ->get();

        if(count($this->friends) > 0)
        {
            $this->selected_friend = $this->friends[0]->reciever_id;
        }

        $this->getCountIncomingFriendRequests();
        $this->tab = 'new-conversation';
    }

    public function startConversation()
    {
        $validated = $this->validate([
            'selected_friend' => 'required|numeric|exists:users,id'
        ]);

        $userIsFriend = UserFriend::where('reciever_id', $this->selected_friend)
                ->where('sender_id', $this->user->id)
                ->where('status', 2)
                ->first();

        if(!$userIsFriend)
        {
            return false;
        }

        $conversation = UserConversation::where(function($query) {
            $query->where('sender_id', $this->user->id)
            ->where('reciever_id', $this->selected_friend);
        })->orWhere(function($query) {
            $query->where('sender_id', $this->selected_friend)
            ->where('reciever_id', $this->user->id);
        })->first();


        if($conversation)
        {
            $this->openConversation($conversation->id);
        }
        else
        {
            $new = UserConversation::create([
                'sender_id' => $this->user->id,
                'reciever_id' => $this->selected_friend,
            ]);

            $this->openConversation($new->id);
        }
    }

    public function openConversation($id)
    {
        $this->time_start = microtime(true);
        $this->selected_conversation = UserConversation::with('messages')
                            ->where('id', $id)
                            ->where(function($query) {
                                $query->where('sender_id', $this->user->id)
                                ->orWhere('reciever_id', $this->user->id);
                            })
                            ->first();

        if(!$this->selected_conversation)
        {
            return false;
        }

        if(count($this->selected_conversation->messages) > 0 
        && $this->selected_conversation->messages[0]->sender_id != $this->user->id
        && $this->selected_conversation->messages[0]->read == 0)
        {
            $this->selected_conversation->messages[0]->update(['read' => 1]);
        }

        $this->getCountIncomingFriendRequests();
        $this->tab = 'conversation';
        $this->dispatch('conversation-opened');
    }

    public function closeConversation()
    {
        $this->time_start = microtime(true);
        $this->selected_conversation = null;
        $this->getAllConversations();

        $this->getCountIncomingFriendRequests();
        $this->tab = 'inbox';
    }

    public function getAllConversations()
    {
        $this->conversations = UserConversation::with('messages')
                            ->where(function($query) {
                                $query->where('sender_id', $this->user->id)
                                ->orWhere('reciever_id', $this->user->id);
                            })
                            ->whereHas('messages')
                            ->latest()
                            ->get();
    }

    public function sendMessage()
    {
        if($this->selected_conversation && $this->message)
        {

            $validated = $this->validate([
                'message' => 'required|string|min:1|max:1200',
            ]);

            try 
            {
                $msg = PrivateMessage::create([
                    'sender_id' => $this->user->id,
                    'conversation_id' => $this->selected_conversation->id,
                    'message' => rtrim($this->message),
                    'read' => 0,
                    'deleted' => 0,
                ]);

                $this->message = null;
                $this->openConversation($this->selected_conversation->id);
            } 
            catch (\Throwable $th) 
            {
                Log::error("Problem sending private message:");
                Log::error($th);
            }
        }
    }

    public function getCountIncomingFriendRequests()
    {
        $this->friend_requests = UserFriend::where('status', 0)
                            ->where('reciever_id', $this->user->id)
                            ->count();
    }

    #[On('friend-request-answered')] 
    public function updatefriend_requests()
    {
        $this->friend_requests = UserFriend::where('status', 0)
                            ->where('reciever_id', $this->user->id)
                            ->count();
    }

    #[On('send-private-message')] 
    public function sendNewMessage()
    {
        $this->sendMessage();
    }

    #[On('private-message-sent')] 
    public function updateMessages($id)
    {
        $this->openConversation($id);
    }

    #[On('open-friend-conversation')] 
    public function openFriendConversation($id)
    {
        if($this->show_chat == false)
        {
            $this->show_chat = true;
        }

        $this->selected_friend = $id;
        $this->startConversation();
    }
}
