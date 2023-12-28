<?php

namespace App\Livewire\Roadmap;

use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Auth;

use App\Models\Idea;

class Ideas extends Component
{

    public $user;
    public $newest = [];
    public $hottest = [];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $this->newest = Idea::limit(10)->latest()->get();
        $this->hottest = Idea::limit(10)->get();

        return view('livewire.roadmap.ideas');
    }
}
