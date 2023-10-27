<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\FirstName;
use App\Models\LastName;

class CharacterNames extends Component
{
    public $gender;
    public $first_name;
    public $last_name;

    public $first_names;
    public $last_names;

    public function mount()
    {
        $this->gender = 'female';
        $this->randomFirstName();
        $this->randomLastName();
    }

    public function render()
    {
        return view('livewire.character-names');
    }

    public function searchFirstName()
    {
        $this->first_names = FirstName::where('name', 'like', $this->first_name.'%')
                                ->where('gender', $this->gender)
                                ->limit(10)
                                ->get();
    }

    public function setFirstName($name)
    {
        $this->first_name = $name;
        $this->first_names = null;
    }

    public function searchLastName()
    {
        $this->last_names = LastName::where('name', 'like', $this->last_name.'%')
                                ->limit(10)
                                ->get();
    }

    public function setLastName($name)
    {
        $this->last_name = $name;
        $this->last_names = null;
    }

    public function changeGender()
    {
        $this->randomFirstName();
    }

    public function randomFirstName()
    {
        $this->first_names = null;
        $this->first_name = FirstName::inRandomOrder()
                                ->where('gender', $this->gender)
                                ->first()
                                ->name;
        
    }

    public function randomLastName()
    {
        $this->last_names = null;
        $this->last_name = LastName::inRandomOrder()
                                ->first()
                                ->name;
        
    }
}
