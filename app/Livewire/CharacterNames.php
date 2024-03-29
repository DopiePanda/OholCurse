<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use DB;

use App\Models\FirstName;
use App\Models\LastName;
use App\Models\LifeLog;
use App\Models\LifeNameLog;

class CharacterNames extends Component
{
    public $gender;

    public $first_name;
    public $last_name;

    public $previous_first_name;
    public $previous_last_name;

    public $first_name_auto_completed;
    public $last_name_auto_completed;

    public $first_names;
    public $last_names;

    public $top_first_names;
    public $top_last_names;

    public function mount()
    {
        $this->gender = 'female';
        $this->randomFirstName();
        $this->randomLastName();

        $this->first_name_auto_completed = false;
        $this->last_name_auto_completed = false;

        //$this->mostPopularFirstNames();
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

        $match = FirstName::where('gender', $this->gender)
                        ->where('name', $this->first_name)
                        ->first();

        if($match && $this->first_name_auto_completed == true)
        {
            $name = FirstName::inRandomOrder()
                                ->where('gender', $this->gender)
                                ->first();

            if($name)
            {
                $this->first_name = $name->name;
                $this->first_name_auto_completed = false;
            }
        }
        else
        {
            $name = FirstName::inRandomOrder()
                                ->where('gender', $this->gender)
                                ->where('name', 'like', $this->first_name.'%')
                                ->where('name', '!=', $this->previous_first_name)
                                ->where('name', '!=', $this->first_name)
                                ->first();

            if($name)
            {
                $this->first_name = $name->name;
                $this->previous_first_name = $name->name;
                $this->first_name_auto_completed = true;
            }
            else
            {
                $this->first_name = FirstName::inRandomOrder()
                                ->where('gender', $this->gender)
                                ->first()
                                ->name;
                
                $this->first_name_auto_completed = false;
            }
            
        }
    }

    public function randomLastName()
    {
        $this->last_names = null;

        $match = LastName::where('name', $this->last_name)
                        ->first();

        if($match && $this->last_name_auto_completed == true)
        {
            $this->last_name = LastName::inRandomOrder()
                                ->first()
                                ->name;

            $this->last_name_auto_completed = false;
        }
        else
        {
            $name = LastName::inRandomOrder()
                    ->where('name', 'like', $this->last_name.'%')
                    ->where('name', '!=', $this->previous_last_name)
                    ->where('name', '!=', $this->last_name)
                    ->first();
       
            if($name)
            {
                $this->last_name = $name->name;
                $this->previous_last_name = $name->name;
                $this->last_name_auto_completed = true;
            }
            else
            {
                $this->last_name = LastName::inRandomOrder()
                                    ->first()
                                    ->name;
                
                $this->last_name_auto_completed = false;
            }
        } 
    }

    public function mostPopularFirstNames()
    {
        $start = Carbon::now();
        $end = $start->clone()->addMonths(1);

        /* $this->top_first_names = LifeLog::query()   
                                    ->with(['name' => function ($query) {
                                        $query->select('character_id', 'name')
                                        ->selectRaw('COUNT(*) AS count')
                                        ->where('name', 'NOT LIKE', 'EVE%')
                                        
                                        ->groupBy('name')
                                        ->orderBy('count', 'desc');

                                        }])
                                    ->whereHas('name', function ($query) {
                                        $query->where('name', '!=', null);
                                    })
                                    ->where('timestamp', '>=', Carbon::now()->subMonth()->timestamp)
                                    ->where('type', '=', 'birth')
                                    ->limit(10)
                                    ->get(); */

        $this->top_first_names = DB::table('life_name_logs')
                                    ->join('life_logs', 'life_name_logs.character_id', '=', 'life_logs.character_id')
                                    ->select(DB::raw("(COUNT(life_name_logs.name)) as count"), 'life_name_logs.character_id', 'life_name_logs.name', 'life_logs.timestamp', 'life_logs.type', 'life_logs.gender')
                                    ->where('life_logs.type', 'birth')
                                    ->where('life_logs.timestamp', '>=', $start->subMonth()->timestamp)
                                    ->where('life_name_logs.name', 'not like', 'EVE%')
                                    ->where('life_name_logs.name', '!=', null)
                                    ->groupBy('life_name_logs.name')
                                    ->orderBy('count', 'desc')
                                    ->limit(10)
                                    ->get();

        $names = LifeLog::whereHas('name')
                    ->with(['name' => function ($query) {
                        $query->select('character_id', 'name')
                        ->where('name', 'NOT LIKE', 'EVE%');
                    }])
                    ->select('character_id')
                    ->where('timestamp', '>=', Carbon::now()->subMonth()->timestamp)
                    ->where('type', '=', 'birth')
                    ->limit(100)
                    ->get();


        dd($names);
    }

    public function updating($property, $value)
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property
 
        if ($property === 'first_name') 
        {
            $this->first_name_auto_completed = false;
        }

        if ($property === 'last_name') 
        {
            $this->last_name_auto_completed = false;
        }
    }
}
