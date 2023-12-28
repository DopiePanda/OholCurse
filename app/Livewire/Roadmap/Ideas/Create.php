<?php

namespace App\Livewire\Roadmap\Ideas;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Redirect;

use Auth;

use App\Models\Idea;

class Create extends Component
{
    public $trix_id;
    public $value;
    public $user_id;
    public $title;
    public $description;

    protected $rules = [];

    public function mount($value = '')
    {
        $this->trix_id = 'trix-' . uniqid();
        $this->value = $value;
        $this->user_id = Auth::user()->id;
    }

    public function render()
    {
        return view('livewire.roadmap.ideas.create');
    }

    public function create()
    {
        $payload = $this->validate([
            'user_id' => 'required|exists:App\Models\User,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $idea = Idea::create($payload);

        return Redirect::route('roadmap.index')
            ->success('Company created!'); 
    }

    public function updatedValue($value)
    {
        $this->description = $value;
    }
}
