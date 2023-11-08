<?php

namespace App\Livewire\Contacts;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Auth;
use Log;

use App\Models\UserContact;
use App\Models\Leaderboard;

class Manage extends ModalComponent
{
    public $user_id;
    public $hash;
    public $player;
    public $contact;

    public $type;
    public $nickname;
    public $phex_hash;



    public function mount($hash = null)
    {
        $this->user_id = Auth::user()->id;
        $this->hash = $hash;
        $this->player = Leaderboard::where('player_hash', $hash)->first();
        $this->contact = UserContact::where('user_id', $this->user_id)->where('hash', $hash)->first();

        if($this->contact)
        {
            $this->type = $this->contact->type;
            $this->nickname = $this->contact->nickname;
            $this->phex_hash = $this->contact->phex_hash ?? null;
        }else
        {
            $this->type = 'friend';
        }
    }

    public function render()
    {
        return view('livewire.contacts.manage');
    }

    public function save(Request $request)
    {
        $this->validate([
            'user_id' => 'required|exists:App\Models\User,id',
            'hash' => 'required|exists:App\Models\Leaderboard,player_hash',
            'type' => 'required|in:friend,enemy,dubious',
            'nickname' => 'required|string',
            'phex_hash' => 'nullable|string',
        ]);

        if($this->phex_hash == '' || $this->phex_hash == null)
        {
            $this->phex_hash = null;
        }

        $contact = UserContact::updateOrCreate(
            [
                'user_id' => $this->user_id, 
                'hash' => $this->player->player_hash
            ],
            [
                'type' => $this->type, 
                'nickname' => $this->nickname,
                'phex_hash' => $this->phex_hash
            ]
        );

        //return redirect(request()->header('Referer'));

        $this->dispatch('contactSaved');
        $this->alert('success', 'You added/updated a person on your list!');
        $this->closeModal();
    }

    public function delete()
    {
        try 
        {
            $this->contact->delete();
            $this->alert('success', 'The contact was removed from your list.');
            $this->closeModal();
            return redirect(request()->header('Referer'));
            
        } catch (\Throwable $th) 
        {
            Log::error("Error deleting contact for user: ".Auth::user()->id);
            Log::error($th);
        }
        
    }
}
