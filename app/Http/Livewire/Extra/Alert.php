<?php

namespace App\Http\Livewire\Extra;

use Livewire\Component;

class Alert extends Component
{
    protected $listeners = ['newAlert' => 'displayNewAlert'];

    public $show;
    public $messages;

    public function mount()
    {
        $this->show = false;
        $this->messages = [];
    }

    public function render()
    {
        return view('livewire.extra.alert');
    }

    public function displayNewAlert($type, $msg)
    {
        //array_push($this->messages, ['id' => count($this->messages)+1,'type' => $type, 'message' => $msg]);
        $this->show = true;
        $this->dispatchBrowserEvent('new-alert', ['id' => count($this->messages)+1,'type' => $type, 'message' => $msg]);
    }

    public function removeAlert($id)
    {
        array_splice($this->messages, $id, 1);
        //unset($this->messages[$id]);
    }

    public function alertSuccess()
    {
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'success',  'message' => 'User Created Successfully!']);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function alertError()
    {
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'error',  'message' => 'Something is Wrong!']);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function alertInfo()
    {
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'info',  'message' => 'Going Well!']);
    }
}
