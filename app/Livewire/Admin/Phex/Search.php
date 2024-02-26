<?php

namespace App\Livewire\Admin\Phex;

use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Database\Eloquent\Builder;

use App\Models\User;
use App\Models\UserContact;

class Search extends Component
{
    use WithPagination;

    public $contacts;

    public $type;
    public $owner;
    public $nickname;
    public $leaderboard;
    public $phex_hash, $phex_name;
    public $olgc_hash, $olgc_name;

    public $has_phex;
    public $has_olgc;

    public function mount()
    {
        $this->type = 'all';
        $this->update();
    }

    public function render()
    {
        return view('livewire.admin.phex.search', ['contacts' => $this->contacts]);
    }

    public function update()
    {
        $contacts = UserContact::with('phex:px_hash,px_name,olgc_hash,olgc_name', 'player:player_hash,leaderboard_name,leaderboard_id', 'user:id,username')
                            ->select('hash', 'phex_hash', 'nickname', 'user_id', 'type');

        if($this->type == 'enemy' || $this->type == 'dubious' || $this->type == 'friend')
        {
            $contacts = $contacts->where('type', $this->type);
        }

        if($this->has_phex)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('px_hash', '!=', null)
                    ->orWhere('px_name', '!=', null);
            });
        }

        if($this->has_olgc)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('olgc_hash', '!=', null)
                    ->orWhere('olgc_name', '!=', null);
            });
        }

        if($this->nickname)
        {
            $contacts = $contacts->where('nickname', 'like', '%'.$this->nickname.'%');
        }

        if($this->owner)
        {
            $contacts = $contacts->whereHas('user', function (Builder $query) {
                $query->where('username', 'like', $this->owner.'%');
            });
        }

        if($this->leaderboard)
        {
            $contacts = $contacts->whereHas('player', function (Builder $query) {
                $query->where('leaderboard_name', 'like', $this->leaderboard.'%');
            });
        }

        if($this->phex_hash)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('px_hash', 'like', $this->phex_hash.'%');
            });
        }

        if($this->phex_name)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('px_name', 'like', $this->phex_name.'%');
            });
        }

        if($this->olgc_hash)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('olgc_hash', 'like', $this->olgc_hash.'%');
            });
        }

        if($this->olgc_name)
        {
            $contacts = $contacts->whereHas('phex', function (Builder $query) {
                $query->where('olgc_name', 'like', $this->olgc_name.'%');
            });
        }

        $this->contacts = $contacts->get();
    }
}
