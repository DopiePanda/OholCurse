<?php

namespace App\Livewire\Conversations;

use LivewireUI\Modal\ModalComponent;
use Auth;
use DB;
use Log;

use App\Models\User;
use App\Models\PrivateMessage;
use App\Models\UserFriend;
use App\Models\UserConversation;

class ManageFriends extends ModalComponent
{
    public $user;
    public $tab;

    public $friends;
    public $requests_out;
    public $requests_in;
    public $blocked;

    public $new_friend;

    public function mount()
    {
        $this->user = Auth::user();
        $this->tab = "friends";

        /* STATUS: 
        0 = Sent, 
        1 = Recieved, 
        2 = Accepted, 
        3 = Rejected, 
        4 = Removed,
        5 = Blocked 
        */
        $this->friends = $this->getFriends();

        $this->requests_out = $this->getOutgoing();

        $this->requests_in = $this->getIncoming();

        $this->blocked = $this->getBlocked();
    }

    public function render()
    {
        return view('livewire.conversations.manage-friends');
    }

    public function setTab($tab)
    {
        switch ($tab) {
            case 'friends':
                $this->friends = $this->getFriends();        
                break;
            case 'requests':
                $this->requests_out = $this->getOutgoing();
                break;
            case 'blocked':
                $this->blocked = $this->getBlocked();
                break;
            
            default:
                # code...
                break;
        }

        $this->requests_in = $this->getIncoming();
        $this->tab = $tab;
    }

    public function getFriends()
    {
        return UserFriend::with('sender', 'reciever')
                    ->where('sender_id', $this->user->id)
                    ->where('status', 2)
                    ->get();
    }

    public function getIncoming()
    {
        return UserFriend::with('sender')
                ->where('status', 0)
                ->where('reciever_id', $this->user->id)
                ->get();
    }

    public function getOutgoing()
    {
        return UserFriend::with('reciever')
                ->whereIn('status', [0, 3])
                ->where('sender_id', $this->user->id)
                ->get();
    }

    public function getBlocked()
    {
        return UserFriend::where('status', 5)
                ->where('sender_id', $this->user->id)
                ->get();
    }

    public function createRequest()
    {
        try 
        {
            $validated = $this->validate([
                'new_friend' => 'required|string|exists:users,username',
            ]);
    
            $target = User::where('username', $this->new_friend)->first();
    
            $state = UserFriend::where('sender_id', $this->user->id)
                        ->where('reciever_id', $target->id)
                        ->first();

            if($state)
            {
                if ($state->status == 0) 
                {
                    return $this->alert('warning', "Friend request already pending");
                }
                elseif ($state->status == 1) 
                {
                    return $this->alert('warning', "You have a pending request from this user");
                }
                elseif ($state->status == 2) 
                {
                    return $this->alert('warning', "You are already friends with this user");
                }
                /*
                elseif ($state->status == 3) 
                {
                    return $this->alert('warning', "Friend request already pending");
                }
                */
                elseif ($state->status == 5) 
                {
                    return $this->alert('warning', "You have blocked this user");
                }
                else
                {
                    $blockedByUser = UserFriend::where('reciever_id', $this->user->id)
                    ->where('sender_id', $target->id)
                    ->where('status', 5)
                    ->first();

                    if ($blockedByUser) 
                    {
                        UserFriend::updateOrCreate([
                            'sender_id' => $this->user->id,
                            'reciever_id' => $target->id,
                        ],[
                            'status' => 3,
                        ]);
                    }
                    else
                    {
                        $request = UserFriend::updateOrCreate([
                            'sender_id' => $this->user->id,
                            'reciever_id' => $target->id,
                        ],[
                            'status' => 0,
                        ]);
        
                        $request = UserFriend::updateOrCreate([
                            'reciever_id' => $this->user->id,
                            'sender_id' => $target->id,
                        ],[
                            'status' => 1,
                        ]);
                    }

                    /* STATUS: 
                    0 = Sent, 
                    1 = Recieved, 
                    2 = Accepted, 
                    3 = Rejected, 
                    4 = Removed,
                    5 = Blocked 
                    */
                }
            }
            else
            {
                $request = UserFriend::updateOrCreate([
                    'sender_id' => $this->user->id,
                    'reciever_id' => $target->id,
                ],[
                    'status' => 0,
                ]);

                $request = UserFriend::updateOrCreate([
                    'reciever_id' => $this->user->id,
                    'sender_id' => $target->id,
                ],[
                    'status' => 1,
                ]);
            }

            $this->requests_in = $this->getIncoming();
            $this->requests_out = $this->getOutgoing();
            $this->friends = $this->getFriends();

            $this->new_friend = null;

            return $this->alert('success', "Friend request sent to: ".$target->username);
        } 
        catch (\Throwable $th) 
        {
            Log::error("Problem adding friend:");
            Log::error($th);
            $this->alert('error', "Error while sending friend request to $target->username");
        }     
    }

    public function removeFriend($friend_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $friend_id, 2);
        $friendRecordFound = $this->getRecord($friend_id, $this->user->id, 2);

        if (!$ownRecordFound || !$friendRecordFound) 
        {
            return $this->alert('error', "Error while removing friend");
        }

        $ownRecordFound->status = 4;
        $ownRecordFound->save();

        $friendRecordFound->status = 4;
        $friendRecordFound->save();

        $this->friends = $this->getFriends();
        return $this->alert('success', "Friend successfully removed");
    }

    public function acceptRequest($user_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $user_id);
        $friendRecordFound = $this->getRecord($user_id, $this->user->id, 0);

        if (!$friendRecordFound) 
        {
            return $this->alert('error', "Friend request could not be found");
        }

        $ownRecordFound->status = 2;
        $ownRecordFound->save();

        $friendRecordFound->status = 2;
        $friendRecordFound->save();

        $this->friends = $this->getFriends();
        $this->requests_in = $this->getIncoming();
        $this->dispatch('friend-request-answered'); 
        return $this->alert('success', "Friend successfully added");
    }

    public function rejectRequest($user_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $user_id);
        $friendRecordFound = $this->getRecord($user_id, $this->user->id, 0);

        if (!$friendRecordFound) 
        {
            return $this->alert('error', "Friend request could not be found");
        }

        $ownRecordFound->status = 3;
        $ownRecordFound->save();

        $friendRecordFound->status = 3;
        $friendRecordFound->save();

        $this->requests_in = $this->getIncoming();
        $this->dispatch('friend-request-answered'); 
        return $this->alert('success', "Request successfully rejected");
    }

    public function cancelRequest($user_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $user_id, 0);
        $friendRecordFound = $this->getRecord($user_id, $this->user->id);

        if (!$ownRecordFound) 
        {
            return $this->alert('error', "Friend request could not be found");
        }

        if ($friendRecordFound && $friendRecordFound->status == 1) 
        {
            $friendRecordFound->status = 4;
            $friendRecordFound->save();
        }

        $ownRecordFound->status = 4;
        $ownRecordFound->save();

        $this->requests_out = $this->getOutgoing();
        return $this->alert('success', "Request successfully cancelled");
    }

    public function blockUser($user_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $user_id);
        $friendRecordFound = $this->getRecord($user_id, $this->user->id);

        if ($ownRecordFound && $ownRecordFound->status == 5) 
        {
            return $this->alert('error', "You have already blocked this user");
        }

        if ($friendRecordFound && $friendRecordFound->status != 5) 
        {
            $friendRecordFound->status = 3;
            $friendRecordFound->save();
        }

        $ownRecordFound->status = 5;
        $ownRecordFound->save();

        $this->friends = $this->getFriends();
        $this->requests_in = $this->getIncoming();
        $this->dispatch('friend-request-answered'); 
        return $this->alert('success', "User has been blocked");
    }

    public function unblockUser($user_id)
    {
        $ownRecordFound = $this->getRecord($this->user->id, $user_id);
        $friendRecordFound = $this->getRecord($user_id, $this->user->id);

        if ($ownRecordFound && $ownRecordFound->status != 5) 
        {
            return $this->alert('error', "You don't have this user blocked");
        }

        if ($friendRecordFound && $friendRecordFound->status != 5) 
        {
            $friendRecordFound->status = 4;
            $friendRecordFound->save();
        }

        $ownRecordFound->status = 4;
        $ownRecordFound->save();

        $this->blocked = $this->getBlocked();
        return $this->alert('success', "User has been unblocked");
    }

    public function getRecord($sender, $reciever, $status = null)
    {
        if ($status) 
        {
            return UserFriend::where('sender_id', $sender)
                ->where('reciever_id', $reciever)
                ->where('status', $status)
                ->first();
        }

        return UserFriend::where('sender_id', $sender)
            ->where('reciever_id', $reciever)
            ->first();
    }

    public function openChat($id)
    {
        //$this->setTab("blocked");
        $this->dispatch('open-friend-conversation', id: $id); 
        //$this->setTab("friends");
    }
}
