<div class="w-full rounded-xl py-12 mx-auto bg-gray-50 dark:bg-gray-900">
    
    @if($contacts)
        <div class="mt-8 p-4">
            
            <div class="text-center text-3xl">Profiles matching: {{ count($contacts) }}</div>

            <div class="w-2/3 lg:w-1/3 flex flex-col mx-auto justify-center items-center">
                <div>
                    <div>
                        <input wire:model="has_phex" wire:change="update" type="checkbox" />
                        <span>Has Phex account</span>
                    </div>
                </div>
                <div>
                    <div>
                        <input wire:model="has_olgc" wire:change="update" type="checkbox" />
                        <span>Has OLGC account</span>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                <table class="mt-8 w-full table-auto divide-y divide-gray-200 text-left dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr class="text-xs bg-white dark:bg-gray-900">
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <input class="w-32 text-black" wire:model="owner" wire:change="update" type="text" placeholder="Filter owner" />
                            </th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <select class="text-black" wire:model="type" wire:change="update" id="">
                                    <option value="all" @if($type == 'all') selected @endif>All</option>
                                    <option value="enemy" @if($type == 'enemy') selected @endif>Enemy</option>
                                    <option value="dubious" @if($type == 'dubious') selected @endif>Dubious</option>
                                    <option value="friend" @if($type == 'friend') selected @endif>Friend</option>
                                </select>
                            </th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <input class="w-40 text-black" wire:model="nickname" wire:change="update" type="text" placeholder="Filter nickname" />
                            </th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <input class="w-40 text-black" wire:model="leaderboard" wire:change="update" type="text" placeholder="Filter leaderboard name" />
                            </th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <input class="block w-40 text-black" wire:model="phex_hash" wire:change="update" type="text" placeholder="Filter PX hash" />
                                <input class="block w-40 text-black" wire:model="phex_name" wire:change="update" type="text" placeholder="Filter PX name" />
                            </th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                <input class="block w-40 text-black" wire:model="olgc_hash" wire:change="update" type="text" placeholder="Filter OLGC hash" />
                                <input class="block w-40 text-black" wire:model="olgc_name" wire:change="update" type="text" placeholder="Filter OLGC name" />
                            </th>
                        </tr>
                        <tr class="text-sm">
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Owner</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Type</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Contact name</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Leaderboard</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Phex</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">OLGC</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @forelse ($contacts as $contact)
                        <tr>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    @if($contact->player)
                                        <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $contact->player->leaderboard_id ?? 0 }}" target="_blank">
                                            {{ $contact->user->username }}
                                        </a>
                                    @else
                                        Leaderboard missing
                                    @endif
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->type }}
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    <a href="{{ route('player.curses', ['hash' => $contact->hash ?? 'error']) }}" target="_blank" title="{{ $contact->nickname }}">
                                        {{ Str::of($contact->nickname)->limit(14, ' ...') }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->player ? $contact->player->leaderboard_name : 'Leaderboard missing'}}
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->px_hash ?? 'No PX Hash' }}
                                </div>
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    <a href="{{ route('player.lives', ['hash' => $contact->hash ?? 'error']) }}" target="_blank">
                                        {{ $contact->phex->px_name ?? 'No PX Name' }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->olgc_hash ?? 'No OLGC Hash' }}
                                </div>
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->olgc_name ?? 'No OLGC Name' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <div class="text-center text-lg text-gray-400">No results</div>
                    @endforelse
                    </tbody>
                </table>
            </div>       
        </div>
    @endif
</div>
