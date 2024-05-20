<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Auth;
use Log;


use App\Models\UserContact;

class ContactList extends Component
{

    protected $listeners = [
        'contactSaved' => '$refresh',
        'contactDeleted' => '$refresh',
    ];

    public $selected;
    public $confirm;
    public $skip;
    public $take;
    public $order;

    public $query;

    public $contacts;
    public $result_count;

    public function mount()
    {
        $this->selected = 'friend';
        $this->skip = 0;
        $this->take = 10;
        $this->order = 'desc';

        //$this->getContacts($this->selected);
    }

    public function render()
    {
        $this->result_count = UserContact::where('user_id', Auth::user()->id)
                                ->where('type', $this->selected);

        if($this->query != null)
        {
            $this->result_count = $this->result_count->where('nickname', 'like', '%'.$this->query.'%');
        }

        $this->result_count = $this->result_count->count();

        $this->contacts = UserContact::with('player')
                        ->where('user_id', Auth::user()->id)
                        ->where('type', $this->selected);

        if($this->query != null)
        {
            $this->contacts = $this->contacts->where('nickname', 'like', '%'.$this->query.'%');
        }

        $this->contacts = $this->contacts->skip($this->skip)
                        ->take($this->take)
                        ->orderBy('created_at', $this->order)
                        ->get();

        return view('livewire.contacts.contact-list');
    }

    public function delete($id)
    {
        if($this->confirm == true)
        {
            $contact = UserContact::find($id);
            $contact->delete();
            $this->alert('info', 'Contact deleted');
            $this->dispatch('contactDeleted');
        }else
        {
            $this->confirm = true;
            $this->alert('warning', 'Click again to confirm deletion.');
        }
        
    }

    public function getContacts($type)
    {
        $this->skip = 0;
        $this->selected = $type;
        $this->query = null;
    }

    /*
    public function getContacts($type)
    {
        if($type != $this->selected)
        {
            $this->selected = $type;
            $this->skip = 0;


            $this->result_count = UserContact::where('user_id', Auth::user()->id)
                                ->where('type', $this->selected)
                                ->count();

            $this->contacts = UserContact::with('player')
                            ->where('user_id', Auth::user()->id)
                            ->where('type', $this->selected)
                            ->skip($this->skip)
                            ->take($this->take)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }else
        {
            $this->result_count = UserContact::where('user_id', Auth::user()->id)
                                ->where('type', $this->selected)
                                ->count();

            $this->contacts = UserContact::with('player')
                            ->where('user_id', Auth::user()->id)
                            ->where('type', $this->selected)
                            ->skip($this->skip)
                            ->take($this->take)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }
    }
    */

    public function updateLimit()
    {
        
    }

    public function updateOrder()
    {

    }

    public function updateQuery()
    {

    }

    public function nextPage()
    {
        if($this->result_count > ($this->skip + $this->take) && $this->result_count > $this->take)
        {
            $this->skip += $this->take;
            //$this->getContacts($this->selected);
        }
    }

    public function previousPage()
    {
        if($this->skip >= 0)
        {
            $this->skip -= $this->take;
        }else
        {
            $this->skip = 0;
        }

        //$this->getContacts($this->selected);
    }
}
