<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
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
        $this->groups = GrieferGroup::with([
            'profiles:id,group_id,player_hash', 
            'profiles.profile:player_hash,leaderboard_name,leaderboard_id',
            'profiles.profile.score:leaderboard_id,curse_score',
            'profiles.report:player_hash,curse_name',
            'profiles.life:id,player_hash,character_id,type,timestamp,pos_x,pos_y,age',
            'profiles.curses_sent:player_hash'
            ])
            ->get();


        $this->profile_group = $this->groups[0]->id;
        $this->sort_by = 'life.id';
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
            $this->groups = GrieferGroup::with([
                'profiles:id,group_id,player_hash', 
                'profiles.profile:player_hash,leaderboard_name,leaderboard_id',
                'profiles.profile.score:leaderboard_id,curse_score',
                'profiles.report:player_hash,curse_name',
                'profiles.lives:player_hash,type,timestamp,pos_x,pos_y,age',
                ])
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
            $this->groups = GrieferGroup::with([
                'profiles:id,group_id,player_hash', 
                'profiles.profile:player_hash,leaderboard_name,leaderboard_id',
                'profiles.profile.score:player_hash,curse_score',
                'profiles.report:player_hash,curse_name',
                'profiles.lives:player_hash,type,timestamp,pos_x,pos_y,age',
                ])
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
                            'nickname' => "$group->name ($i) [Import]",
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

    public function scrapeLeaderboardPage($id)
    {
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        $base_url = 'https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id=';

        $url = $base_url.$id;

        $website = $client->request('GET', $url);

        $score = $website->filter('table')->eq(1);

        $cells = $score->filter('tr > td')->each(function($td, $i) {
            return $td;
        });

        // Time: $cells[10]->text()
        // Relation: $cells[6]->text()

        $i_name = 4;
        $i_relation = 6;
        $i_time = 10;

        $rows = 6;
        $index = 1;

        $payload = [];

        while ($index <= $rows) 
        {
            array_push($payload, [
                'name' => $cells[$i_name]->text(),
                'relation' => $cells[$i_relation]->text(),
                'time' => $cells[$i_time]->text(),
            ]);

            $i_name += 7;
            $i_relation += 7;
            $i_time += 7;

            $index++;
        }

        /*
        Row 1: name - [4], relation - [6], time - [10]
        Row 2: name - [11], relation - [13], time - [17]
        Row 3: name - [18], relation - [20], time - [24]
        Row 4: name - [25], relation - [27], time - [31]
        Row 5: name - [32], relation - [34], time - [38]
        Row 6: name - [39], relation - [41], time - [45]


        */


        dd($payload);
    }
}
