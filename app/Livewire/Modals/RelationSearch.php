<?php

namespace App\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use Log;

use App\Models\LifeLog;
use App\Models\Leaderboard;

class RelationSearch extends ModalComponent
{
    public $input;

    public $origin, $origin_hash;
    public $target;

    public $origin_was_parent;
    public $origin_was_child;
    public $origin_was_sibling;

    public function mount($hash)
    {
        $this->origin_hash = $hash;

        try 
        {
            $this->origin = Leaderboard::where('player_hash', $hash)->firstOrFail();
        } 
        catch(\Exception $e)
        {
            Log::error("Relation search - Could not find leaderboard for hash: $hash");
            Log::error($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.modals.relation-search');
    }

    public function search()
    {
        $this->validate([
            'origin_hash' => 'required',
            'input' => 'required|exists:App\Models\Leaderboard,leaderboard_name|different:origin.leaderboard_name',
        ]);

        $this->origin_was_parent = null;
        $this->origin_was_child = null;
        $this->origin_was_sibling = null;

        $this->target = Leaderboard::where('leaderboard_name', $this->input)->first();

        $lives_origin = LifeLog::with('name')
                ->where('type', 'birth')
                ->where('player_hash', $this->origin_hash)
                ->where('parent_id', '!=', 0)
                ->select('character_id', 'parent_id')
                ->get();

        $origin_lives = $lives_origin->pluck('character_id');
        $origin_parents = $lives_origin->pluck('parent_id');

        $lives_target = LifeLog::with('name')
                ->where('type', 'birth')
                ->where('player_hash', $this->target->player_hash)
                ->where('parent_id', '!=', 0)
                ->select('character_id', 'parent_id')
                ->get();

        $target_lives = $lives_target->pluck('character_id');
        $target_parents = $lives_target->pluck('parent_id');

        $this->origin_was_parent = LifeLog::with('name', 'parent', 'leaderboard')
        ->where('type', 'birth')
        ->where('player_hash', $this->target->player_hash)
        ->whereIn('parent_id', $origin_lives)
        ->whereHas('death', function(Builder $query){
            $query->where('age', '>', 1);
        })
        ->select('character_id', 'parent_id', 'timestamp', 'player_hash')
        ->orderBy('timestamp', 'desc')
        ->get();

        $this->origin_was_child = LifeLog::with('name', 'parent', 'leaderboard')
        ->where('type', 'birth')
        ->where('player_hash', $this->origin_hash)
        ->whereIn('parent_id', $target_lives)
        ->whereHas('death', function(Builder $query){
            $query->where('age', '>', 1);
        })
        ->select('character_id', 'parent_id', 'timestamp', 'player_hash')
        ->orderBy('timestamp', 'desc')
        ->get();

        $this->origin_was_sibling = LifeLog::with('name', 'parent', 'leaderboard')
        ->where('type', 'birth')
        ->where('player_hash', $this->target->player_hash)
        ->whereIn('parent_id', $origin_parents)
        ->whereHas('death', function(Builder $query){
            $query->where('age', '>', 1);
        })
        ->select('character_id', 'parent_id', 'timestamp', 'player_hash')
        ->orderBy('timestamp', 'desc')
        ->get();
    }

    public function compareToLoggedInUser()
    {   
        $this->input = Auth::user()->leaderboard->leaderboard_name;
        $this->search();
    }

    public function messages(): array
    {
        return [
            'origin.required' => 'Origin required',
            'input.required' => 'Target leaderboard name required',
            'input.exists' => 'Leaderboard name not found',
            'input.different' => 'Enter a leaderboard name that is not same as the origin name',
        ];
    }
}
