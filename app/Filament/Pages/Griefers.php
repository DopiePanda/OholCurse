<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Log;
use Carbon\Carbon;

use App\Models\GrieferGroup;
use App\Models\GrieferProfile;
use App\Models\CurseLog;
use App\Models\UserContact;

class Griefers extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    protected static string $view = 'filament.pages.griefers';

    protected static ?string $title = 'Griefer Inspector';

    public $groups;
    public $group_name, $group_note, $group_id;

    public $profile_group, $profile_hash, $profile_id;

    public $match_hash;
    public $matches;

    public $sort_by;
    public $order_by_desc;

    public function mount()
    {
        $this->groups = GrieferGroup::with(['profiles:id,group_id,player_hash'])
            ->get();

        $this->profile_group = $this->groups[0]->id;
        $this->sort_by = 'life.timestamp';
        $this->order_by_desc = 1;
    }

    public function saveGroup()
    {
        $this->validate([
            'group_name' => 'required|string',
            'group_note' => 'nullable|string',
            'group_id' => 'nullable|numeric',
        ]);

        try 
        {
            if(!$this->group_id)
            {
                GrieferGroup::updateOrCreate(
                    [
                        'name' => $this->group_name,
                    ], 
                    [
                        'note' => $this->group_note,
                    ]
                );

                Notification::make()
                    ->title('The group was created!')
                    ->color('success')
                    ->send();
            }
            else
            {
                $group = GrieferGroup::find($this->group_id);
                $group->name = $this->group_name;
                $group->note = $this->group_note;
                $group->save();

                Notification::make()
                    ->title('The group was updated!')
                    ->color('success')
                    ->send();
            }

            $this->reset('group_name', 'group_note', 'group_id');
            $this->groups = GrieferGroup::with(['profiles:id,group_id,player_hash'])
            ->get();

        } catch (\Throwable $th) 
        {
            Log::error($th);
        }
    }

    public function editGroup($id)
    {
        $group = GrieferGroup::find($id);
        $this->group_name = $group->name;
        $this->group_note = $group->note;
        $this->group_id = $group->id;

        $this->dispatch('edit-group')->self();
    }

    public function saveProfile()
    {
        $this->validate([
            'profile_group' => 'required|numeric|exists:griefer_groups,id',
            'profile_hash' => 'required|string',
            'profile_id' => 'nullable|numeric',
        ]);

        try 
        {
            if(!$this->profile_id)
            {
                GrieferProfile::updateOrCreate(
                    [
                        'player_hash' => $this->profile_hash,
                    ], 
                    [
                        'group_id' => $this->profile_group,
                    ]
                );

                Notification::make()
                    ->title('The profile was added!')
                    ->color('success')
                    ->send();
            }
            else
            {
                $profile = GrieferProfile::find($this->profile_id);
                $profile->group_id = $this->profile_group;
                $profile->player_hash = $this->profile_hash;
                $profile->save();

                Notification::make()
                    ->title('The profile was updated!')
                    ->color('success')
                    ->send();
            }

            $this->reset('profile_hash', 'profile_id');
            $this->groups = GrieferGroup::with(['profiles:id,group_id,player_hash'])
            ->get();

        } catch (\Throwable $th) 
        {
            Log::error($th);
        }
    }

    public function editProfile($id)
    {
        $profile = GrieferProfile::find($id);
        $this->profile_group = $profile->group_id;
        $this->profile_hash = $profile->player_hash;
        $this->profile_id = $profile->id;

        $this->dispatch('edit-profile')->self();
    }

    public function matchCurses()
    {
        $this->validate([
            'match_hash' => 'required|exists:leaderboards,player_hash',
        ]);

        $hashes = GrieferProfile::select('player_hash')->get()->pluck('player_hash');

        $this->matches = CurseLog::with(['leaderboard:player_hash,leaderboard_name', 'griefer_profile:group_id,player_hash'])
            ->select('reciever_hash')
            ->where('type', 'curse')
            ->where('player_hash', $this->match_hash)
            ->whereIn('reciever_hash', $hashes)
            ->orderBy('timestamp', 'desc')
            ->groupBy('reciever_hash')
            ->get()
            ->pluck('reciever_hash');

        //dd($matches);
    }

    public function addToContacts()
    {
        $user = auth()->user();

        foreach ($this->groups as $group) 
        {
            $i = 1;

            foreach ($group->profiles as $profile) 
            {
                try 
                {
                    UserContact::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'hash' => $profile->player_hash,
                        ], 
                        [
                            'type' => 'enemy',
                            'nickname' => "$group->name ($i)",
                        ]
                    );

                    $i++;
                } 
                catch (\Throwable $th) 
                {
                    Log::error($th);
                }
            }
        }

        Notification::make()
                    ->title('Griefers added as enemies to personal contacts!')
                    ->color('success')
                    ->send();
    }

    public function updateSort()
    {}
}
