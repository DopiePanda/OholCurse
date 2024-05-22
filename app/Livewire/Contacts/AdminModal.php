<?php

namespace App\Livewire\Contacts;

use LivewireUI\Modal\ModalComponent;

use App\Models\UserContact;

class AdminModal extends ModalComponent
{
    public $leaderboard;
    public $records;

    public function mount($hash, $leaderboard)
    {
        $this->leaderboard = $leaderboard;
        $this->records = UserContact::with('user', 'olgc', 'phex')
            ->where('hash', $hash)
            ->orderBy('comment', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.contacts.admin-modal');
    }
}