<?php

namespace App\Livewire\Admin\Leaderboards;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
use Log;

use App\Models\GameLeaderboard;
use App\Models\GameObject;

class Create extends ModalComponent
{
    use WithFileUploads;

    public $all_objects;

    public $type = 'weekly';
    public $enabled;
    public $multi, $multi_objects;
    public $object_id;
    public $label;
    public $page_title;
    public $limit;

    public $image_url = null;
    public $image_file = null;

    private $storage_path = 'assets/images/objects/';

    protected $rules = [
        'type' => 'required|in:weekly,monthly',
        'multi' => 'required|boolean',
        'multi_objects' => 'exclude_if:multi,false|array',
        'enabled' => 'required|boolean',
        'object_id' => 'sometimes|exclude_if:multi,true|exists:game_objects,id',
        'label' => 'required|string',
        'page_title' => 'required|string',
        'limit' => 'required|in:10,25,50,100',
        'image_url' => 'url|required_if:image_file,null',
        'image_file' => 'sometimes|required_if:image_url,null',
    ];

    public function mount()
    {
        $leaderboards = GameLeaderboard::select('object_id')->get();
        $this->all_objects = GameObject::whereNotIn('id', $leaderboards)->orderBy('id', 'asc')->get();

        $this->type = 'weekly';
        $this->multi = 0;
        $this->multi_objects = [];
        $this->enabled = 1;
        $this->limit = 10;
        $this->label = 'Most ';
    }

    public function render()
    {
        return view('livewire.admin.leaderboards.create');
    }

    public function setGameObject($id)
    {
        $this->object_id = $id;
        dd($id);
    }

    public function save()
    {
        //dd($this->multi_objects);

        $validated = $this->validate();

        if($this->multi == true)
        {
            $validated['object_id'] = $validated['multi_objects'][0];
            $validated['multi_objects'] = json_encode($validated['multi_objects']);
        }else
        {
            $validated['multi_objects'] = null;
        }

        if($this->image_url != null)
        {
            $validated['image'] = $this->storeImageFromUrl();
        }else
        {
            $validated['image'] = $this->storeImageFromFile();
        }
        
        $leaderboard = GameLeaderboard::create($validated);
        Artisan::call('app:update-leaderboard-records', ['id' => $leaderboard->id]);

        $this->closeModalWithEvents([
	        'leaderboardCreated', // Emit global event
        ]);
    }

    public function storeImageFromUrl()
    {
        $file = file_get_contents($this->image_url);
        $name = basename($this->image_url);

        $path = Storage::disk('public')->put($this->storage_path.$name, $file);

        return $this->storage_path.$name;
    }

    public function storeImageFromFile()
    {
        $name = $this->image_file->getClientOriginalName();
        $extension = $this->image_file->getClientOriginalExtension();

        $path = $this->image_file->storeAs($this->storage_path, $name, 'public');

        return $path;
    }

    public function toggleMulti()
    {
        if($this->multi == 1)
        {
            $this->all_objects = GameObject::orderBy('id', 'asc')->get();
        }else
        {
            $leaderboards = GameLeaderboard::select('object_id')->get();
            $this->all_objects = GameObject::whereNotIn('id', $leaderboards)->orderBy('id', 'asc')->get();
        }
    }
}
