<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div id="editGroup" class="p-4 border rounded-lg">
            <div class="text-2xl uppercase text-center">Add or edit group</div>
            <div class="mt-4">
                <form wire:submit="saveGroup">
                    <div class="mt-4">
                        <div>
                            <label for="group_name">Griefer name</label>
                        </div>
                        <div class="mt-2">
                            <input wire:model="group_name" class="w-full text-black" id="group_name" type="text" placeholder="Enter group name">
                        </div>
                        <div>
                            @error('group_name') <span class="error">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="mt-4">
                        <div>
                            <label for="group_note">Comment</label>
                        </div>
                        <div class="mt-2">
                            <textarea wire:model="group_note" class="w-full text-black" id="group_note" placeholder="Enter comment" rows="3"></textarea>
                        </div>
                        <div>
                            @error('group_note') <span class="error">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="mt-2">
                            <input wire:model="group_id" type="hidden">
                            <button class="w-full p-2 bg-skin-fill dark:bg-skin-fill-dark" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="editProfile" class="p-4 border rounded-lg flex flex-col">
            <div class="text-2xl uppercase text-center">Add or edit profile</div>
            <div class="grow mt-4">
                <form wire:submit="saveProfile" class="h-full flex flex-col justify-between">

                        <div class="mt-4">
                            <div>
                                <label for="profile_group">Group</label>
                            </div>
                            <div class="mt-2">
                                <select wire:model="profile_group" class="w-full text-black" id="profile_group">
                                    @forelse ($groups as $group)
                                        <option value="{{ $group->id }}" @if($profile_group == $group->id) selected @endif>{{ $group->name }}</option>
                                    @empty
                                        <option value="" disabled>No groups found</option>
                                    @endforelse
                                </select>
                            </div>
                            <div>
                                @error('profile_group') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mt-4">
                            <div>
                                <label for="profile_hash">Hash</label>
                            </div>
                            <div class="mt-2">
                                <input wire:model="profile_hash" class="w-full text-black" id="profile_hash" type="text" placeholder="Enter player hash">
                            </div>
                            <div>
                                @error('profile_hash') <span class="error">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="mt-2">
                                <input wire:model="profile_id" type="hidden">
                                <button class="w-full p-2 bg-skin-fill dark:bg-skin-fill-dark" type="submit">Submit</button>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="p-4 flex flex-col justify-center border rounded-lg">
            <div class="text-2xl text-center uppercase">Sync all to personal contacts</div>
            <button wire:click="addToContacts" type="button" class="mt-6 p-4 w-full lg:w-1/2 mx-auto text-center cursor-pointer bg-skin-fill dark:bg-skin-fill-dark text-white">
                Sync all profiles
            </button>
        </div>
        <div class="p-4 border rounded-lg">
            <div class="text-2xl text-center uppercase">Check if profiles are cursed by hash</div>
            <form wire:submit="matchCurses">
                <div class="mt-4">
                    <div>
                        <label for="match_hash">Player hash to check</label>
                    </div>
                    <div class="mt-2">
                        <input wire:model="match_hash" class="w-full text-black" id="match_hash" type="text" placeholder="Enter a player hash to check">
                    </div>
                    <div>
                        @error('match_hash') <span class="error">{{ $message }}</span> @enderror 
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mt-2">
                        <button class="w-full p-2 bg-skin-fill dark:bg-skin-fill-dark" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($groups as $group)  
        <div class="w-full p-4 border rounded-lg">
            <div class="grid grid-cols-2">
                <div>
                    <div class="text-xl font-bold uppercase">{{ $group->name }}</div>
                    <div class="mt-2 text-sm">
                        {{ $group->note }}
                    </div>
                </div>
                <div wire:click="editGroup({{ $group->id }})" class="text-right">
                    Edit
                </div>
            </div>
            <div class="w-40 my-6 border-b-4"></div>
            <div class="relative overflow-x-auto">
                <table class="border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800 text-sm shadow-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700">
                        <tr>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                #
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Leaderboard name
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Curse name
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Curses
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Last death
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Age
                            </th>
                            <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($group->profiles as $account)
                            @if($matches && $matches->contains($account->player_hash))
                                <tr class="bg-green-800">
                            @elseif($matches && $matches->doesntContain($account->player_hash))
                                <tr class="bg-red-800">
                            @else
                                <tr>
                            @endif
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {{ $loop->index+1 }}
                                </td>
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <div>{{ $account->profile->leaderboard_name ?? 'N/A' }}</div>
                                    <div class="text-xs">{{ $account->player_hash }}</div>
                                </td>
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {!! $account->report->curse_name ?? '<i>N/A</i>' !!}
                                </td>
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {{ $account->profile->score->curse_score ?? 'N/A' }}
                                </td>
                                @php
                                    $life = $account->lives->where('type', 'death')->sortByDesc('id')->first();
                                    if($life)
                                    {
                                        $death = \Carbon\Carbon::parse(date('Y-m-d', $life->timestamp));
                                        $now = \Carbon\Carbon::now();

                                        $diff = $death->diffInDays($now);
                                    }
                                    
                                @endphp
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400 @if($life && $diff <= 5) bg-orange-800 @endif">
                                    <div>{{ $life ? date('Y-m-d H:i', $life->timestamp)." ($diff days ago)" : 'N/A' }}</div>
                                    <div class="text-xs">[{{ $life ? $life->pos_x.', '.$life->pos_y : 'N/A' }}]</div>
                                </td>
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    {{ $life ? $life->age : 'N/A' }}
                                </td>
                                <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <span wire:click="editProfile({{ $account->id }})">Edit</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No profiles added</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="w-full p-4 border rounded-lg">
            No groups found
        </div>
    @endforelse

    @script
    <script>
        const group = document.getElementById("editGroup");
        const profile = document.getElementById("editProfile");

        $wire.on('edit-group', () => {
            group.scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
        });

        $wire.on('edit-profile', () => {
            profile.scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
        });
    </script>
    @endscript

</x-filament-panels::page>
