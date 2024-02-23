<div class="w-full rounded-xl py-12 mx-auto bg-gray-50 dark:bg-gray-900">
    <form wire:submit.prevent="process">
        <div class="w-full px-6 md:px-0 md:w-2/5 mx-auto flex flex-col text-center">
            <div class="w-full grow">
                <input style="color: #333;" class="w-full rounded-lg" wire:model="search" type="text" placeholder="Enter character life name" />
            </div>
            <div class="mt-2 w-full grow text-left">
                <input style="color: #333;" class="mr-2" id="live" wire:model="live" type="checkbox" />
                <label for="live">Force live fetching</label>
            </div>
            <div class="mt-4 w-full grow">
                <button class="w-full mt-2 py-2 text-white bg-primary-500 dark:bg-primary-500">
                    Find player profile
                </button>
            </div>
            
            <div class="mt-12 text-primary-500" wire:loading>
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                <div>Searching for player</div>
                <div class="text-gray-400 text-sm">This can take a minute or two.</div>
            </div>
        </div>
    </form>
    
    @if($results)
        <div class="mt-8 p-4">
            
            <div class="text-center text-3xl">Profiles found</div>

            <div class="divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                <table class="mt-8 w-full table-auto divide-y divide-gray-200 text-left dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr class="text-sm">
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Leaderboard ID</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Leaderboard name</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Avg. age</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Gene score</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Life name</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Life age</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Died between</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Last crawled</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @forelse ($results as $result)
                        @if($result["entries"][0]["rel"] == "You")
                            <tr>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $result['leaderboard_id'] }}" target="_blank">
                                            {{ $result["leaderboard_id"] }}
                                        </a>
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        <a href="{{ route('player.curses', ['hash' => $result['ehash'] ?? 'error']) }}" target="_blank">
                                            {{ $result["leaderboard_name"] }}
                                        </a>
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        {{ round($result["avg_age"], 2) }}
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        {{ round($result["entries"][0]["new_score"], 2) }}
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        <a href="{{ route('player.lives', ['hash' => $result['ehash'] ?? 'error']) }}" target="_blank">
                                            {{ $result["entries"][0]["name"] }}
                                        </a>
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        {{ round($result["entries"][0]["age"], 2) }}
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        <div>{{ date('Y-m-d H:i', $result["entries"][0]["death_min"]) }}</div>
                                        <div>{{ date('Y-m-d H:i', $result["entries"][0]["death_max"]) }}</div>
                                    </div>
                                </td>
                                <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                    <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                        {{ date('Y-m-d H:i', $result["last_crawled"]) }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <div class="text-center text-lg text-gray-400">No results</div>
                    @endforelse
                    </tbody>
                </table>
            </div>       
        </div>
    @endif

    @if($request_time)
        <div class="mt-8 w-full text-center text-sm text-gray-800 dark:text-gray-200">
            Data provided by Selbs <a class="text-primary-500" href="https://yum.selb.io/yumdb/api/v1/" target="_blank">YumDB</a>
        </div>

        <div class="mt-1 w-full text-center text-sm text-gray-700 dark:text-gray-400">
            Request took {{ round($request_time, 3) }} seconds
        </div>
    @endif
</div>
