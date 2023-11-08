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

class Edit extends ModalComponent
{

    public $leaderboard;

    public $type;
    public $enabled;
    public $object_id;
    public $label;
    public $page_title;
    public $limit;
    public $image;

    public $image_url = null;
    public $image_file = null;

    public $clicked_delete = false;

    private $storage_path = 'assets/images/objects/';

    protected $rules = [
        'type' => 'required|in:weekly,monthly',
        'enabled' => 'required|boolean',
        'object_id' => 'required|exists:game_objects,id',
        'label' => 'required|string',
        'page_title' => 'required|string',
        'limit' => 'required|in:10,25,50,100',
        'image_url' => 'nullable|url',
        'image_file' => 'nullable|image',
        'image' => 'required|string',
    ];

    public function mount($id)
    {
        $this->leaderboard = GameLeaderboard::find($id);

        $this->type = $this->leaderboard->type;
        $this->enabled = $this->leaderboard->enabled;
        $this->object_id = $this->leaderboard->object_id;
        $this->label = $this->leaderboard->label;
        $this->page_title = $this->leaderboard->page_title;
        $this->limit = $this->leaderboard->limit;
        $this->image = $this->leaderboard->image;
    }

    public function render()
    {
        return view('livewire.admin.leaderboards.edit');
    }

    public function save()
    {
        if($this->image_url != null && $this->image_file == null)
        {
            $this->image = $this->storeImageFromUrl();
        }
        if($this->image_url == null && $this->image_file != null)
        {
            $this->image = $this->storeImageFromFile();
        }

        $validated = $this->validate();

        $this->leaderboard->type = $validated['type'];
        $this->leaderboard->enabled = $validated['enabled'];
        $this->leaderboard->object_id = $validated['object_id'];
        $this->leaderboard->label = $validated['label'];
        $this->leaderboard->page_title = $validated['page_title'];
        $this->leaderboard->limit = $validated['limit'];
        $this->leaderboard->image = $validated['image'];

        $this->leaderboard->save();

        Artisan::call('app:update-leaderboard-records', ['id' => $this->leaderboard->id]);

        $this->closeModalWithEvents([
	        'leaderboardUpdated', // Emit global event
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

    public function confirmDelete()
    {
        $this->clicked_delete = true;
    }

    public function delete()
    {
        if($this->clicked_delete == true)
        {
            $this->leaderboard->delete();

            $this->closeModalWithEvents([
                'leaderboardDeleted', // Emit global event
            ]);
        }
        
    }
}
