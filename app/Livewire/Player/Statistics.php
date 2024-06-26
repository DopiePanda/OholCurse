<?php

namespace App\Livewire\Player;

use Livewire\Component;
use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\Leaderboard;
use App\Models\LifeLog;
use App\Models\FoodLog;
use App\Models\User;

class Statistics extends Component
{
    public $hash;
    public $profile;
    public $donator;
    public $total_lives;

    public $start_time;

    public $hours_played;
    public $children_born;
    public $foods_eaten;
    public $food_lives;
    public $all_lives;
    public $eve_lives;
    public $ghost_lives;
    public $times_killed;
    public $player_kills;

    public $female_names_recieved, $female_names_given;
    public $male_names_recieved, $male_names_given;

    public $top_foods_eaten;
    public $death_causes;

    public function mount($hash)
    {
        if($hash == '')
        {
            abort(404);
        }

        $this->start_time = microtime(true);

        if(strlen($hash) < 40)
        {
            $leaderboard = Leaderboard::where('leaderboard_id', $hash)->first();
            $this->hash = $leaderboard->player_hash;
        }
        else
        {
            $this->hash = $hash;
        }
        
        $this->eve_lives = 0;

        $this->getPlayerStats();

        $this->profile = Leaderboard::with('score')
                    ->where('player_hash', $this->hash)
                    ->select('leaderboard_name', 'leaderboard_id', 'player_hash')
                    ->orderBy('id', 'desc')
                    ->first();

        $this->donator = User::where('donator', 1)->where('player_hash', $this->hash)->first() ?? null;
    }

    public function render()
    {
        return view('livewire.player.statistics');
    }

    public function getPlayerStats()
    {
        $lives = $this->getLivesLived();
        //$kills = $this->getPlayerKills();
        $children = $this->getChildrenBorn($lives);
        $foods = $this->getFoodsEaten($lives);
        $food_lives = $this->getFoodLives();
        
        $names_recieved = $this->sortPopularNames($lives, 'recieved', 10);
        $names_given = $this->sortPopularNames($children, 'given', 10);
    }

    public function getLivesLived()
    {
        $lives = LifeLog::with('death:timestamp,character_id,age,died_to', 'name:character_id,name')
                    ->select('timestamp', 'character_id', 'parent_id', 'gender', 'family_type')
                    ->where('player_hash', $this->hash)
                    ->where('type', 'birth')
                    ->orderBy('character_id', 'desc')
                    ->get()
                    ->toArray();

        $life_collect = collect($lives);

        $this->total_lives = $life_collect->count();
        

        $minutes_played = $life_collect->pluck('death.age')->sum();
        $this->hours_played = $minutes_played / 60;

        $deaths = [];

        foreach ($lives as $life) 
        {
            if($life['death'] && $life['death']['died_to'] && str_contains($life['death']['died_to'], 'killer_') === false)
            {
                $deaths[$life['death']['died_to']][] = $life;
            }
            else
            {
                if($life['death'] && $life['death']['died_to'])
                {
                    $killer = explode('_', $life['death']['died_to']);
                    $deaths['killed'][$killer[1]] = $life;
                }
            }
        }

        array_multisort(array_map('count', $deaths), SORT_DESC, $deaths);
        $this->death_causes = $deaths;

        if(isset($deaths['killed']))
        {
            $this->times_killed = count($deaths['killed']) ?? 0;
        } 

        //dd($this->death_causes);

        $ghost_lives = $life_collect->filter(function ($item) {
            if($item['death'] != null)
            {
                return ($item['death']['timestamp'] - $item['timestamp']) > 3696;
            }

            return false;
        });

        $this->ghost_lives['total'] = $ghost_lives->count();

        $this->ghost_lives['filtered'] = $ghost_lives->filter(function ($item) {
            if($item['death'] != null)
            {
                return $item['death']['timestamp'] > 1698713999;
            }

            return false;
        })->count();

        return $life_collect;
    }

    public function getPlayerKills()
    {
        $player_lives = LifeLog::where('player_hash', $this->hash)->where('type', 'death')->pluck('character_id');
        
        $player_kills = 0;

        foreach ($player_lives as $life)
        {
            $kill = LifeLog::where('died_to', 'killer_'.$life)->where('type', 'death')->first();
            if($kill)
            {
                $player_kills = $player_kills + 1;
            }
        }
        
        $this->player_kills = $player_kills;
    }

    public function getChildrenBorn($collection)
    {
        $female_lives = $collection->where('gender', 'female');
        $female_ids = $female_lives->pluck('character_id');
        $children = LifeLog::with('name:character_id,name')
                    ->select('character_id', 'gender', 'parent_id')
                    ->whereIn('parent_id', $female_ids)
                    ->where('type', 'birth')
                    ->get()
                    ->toArray();

        $child_collect = collect($children);
        $this->children_born = $child_collect->count();

        return $child_collect;
    }

    public function getFoodsEaten($collection)
    {
        $all_ids = $collection->pluck('character_id');

        $foods = FoodLog::with('object:id,name')
                    ->select('object_id')
                    ->whereIn('character_id', $all_ids)
                    ->get()
                    ->toArray();

        $food_collect = collect($foods);

        $this->foods_eaten = $food_collect->count();

        $grouped = $food_collect->groupBy('object.name')->sortByDesc(function($item, $key) {
            return count($item);
        });

        $this->top_foods_eaten = $grouped->take(25);

        return $grouped;
    }

    public function getFoodLives()
    {
        $food_logs_release_date = 1701458586;
        $this->all_lives  = LifeLog::where('player_hash', $this->hash)->where('age', '>', 3)->where('type', 'death')->count();
        $this->food_lives  = LifeLog::where('player_hash', $this->hash)->where('age', '>', 3)->where('type', 'death')->where('timestamp', '>', $food_logs_release_date)->count();
    }

    public function sortPopularNames($collection, $type, $limit)
    {
        $names_recieved = [];

        foreach ($collection as $life) 
        {
            if($life['name'])
            {
                $first_name = explode(' ', $life['name']['name'])[0];

                if ($life['parent_id'])
                {
                    if(isset($names_recieved[$first_name]))
                    {
                        $names_recieved[$first_name]['count'] += 1;
                    }
                    else
                    {
                        //$names_recieved[] = $first_name;
                        $names_recieved[$first_name] = ['name' => $first_name, 'count' => 1, 'gender' => $life['gender']];
                    }
                }
                else
                {
                    $this->eve_lives++;
                }
            }
            
        }
        $names_collect = collect($names_recieved);

        $sorted = $names_collect->sortByDesc(function ($name, $key) {
            return $name['count'];
        });
        

        if($type == 'recieved')
        {
            $this->male_names_recieved = $sorted->where('gender', 'male')->take($limit);
            $this->female_names_recieved = $sorted->where('gender', 'female')->take($limit);
        }

        if($type == 'given')
        {
            $this->male_names_given = $sorted->where('gender', 'male')->take($limit);
            $this->female_names_given = $sorted->where('gender', 'female')->take($limit);
        }

        return $sorted;

    }
}
