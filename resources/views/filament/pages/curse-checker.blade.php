<x-filament-panels::page>
    <div class="p-4 w-full mx-auto bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
        <div class="text-3xl text-center">
            Check for online cursers
        </div>
        <div class="w-1/3 mt-4 mx-auto">
            <form wire:submit="search">
                <div class="mt-4 text-left">
                    <div class="mb-2">
                        <label for="hash">
                            Player hash
                        </label>
                    </div>
                    <div>
                        <input wire:model="hash" id="hash" class="w-full text-black" type="text" placeholder="Enter a player hash">
                    </div>
                    <div>
                        @error('hash') <span class="error">{{ $message }}</span> @enderror 
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mt-2">
                        <button class="w-full p-2 bg-skin-fill dark:bg-skin-fill-dark" type="submit">Submit</button>
                    </div>
                </div>
            </form>

            <div wire:loading class="w-full mx-auto text-center mt-12 text-primary-500">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                <div class="text-skin-muted dark:text-skin-muted-dark">Searching for previous relationships</div>
            </div>
        </div>
    </div>

    @if($results && count($results) > 0)
        <div class="p-4 w-full mx-auto bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <table class="border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white dark:bg-slate-800 text-sm shadow-sm">
                <thead class="bg-slate-50 dark:bg-slate-700">
                    <tr>
                        <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                            Leaderboard name
                        </th>
                        <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                            Character
                        </th>
                        <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                            Death
                        </th>
                        <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                            Last crawl
                        </th>
                        <th class="border border-slate-300 dark:border-slate-600 font-semibold p-4 text-slate-900 dark:text-slate-200 text-left">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results->sortByDesc('life_end') as $profile)
                        @if(time() - $profile['life_end'] < (60 * 60 * 24 * 2))
                            <tr class="bg-red-800">
                        @else
                            <tr>
                        @endif
                            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-200">
                                <div>
                                    <a target="_blank" href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $profile['id'] ?? '' }}">
                                        {{ $profile['name'] ?? 'N/A' }}
                                    </a>
                                </div>
                                <div class="mt-2 text-xs">
                                    <a target="_blank" href="{{ route('player.interactions', ['hash' => $profile['hash']]) }}">{{ $profile['hash'] }}</a>
                                </div>
                            </td>
                            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-200">
                                <div>{{ $profile['life_name'] ?? 'N/A' }}</div>
                                <div>Age: {{ $profile['life_age'] }}</div>
                            </td>
                            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-200">
                                <div>{{ \Carbon\Carbon::createFromTimestamp($profile['life_end'])->diffForHumans(['parts' => 2]) }}</div>
                                <div>{{ date('Y-m-d H:i:s', $profile['life_end']) }}</div>
                            </td>
                            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-200">
                                <div>{{ \Carbon\Carbon::createFromTimestamp($profile['last_crawled'])->diffForHumans(['parts' => 2]) }}</div>
                                <div>{{ date('Y-m-d H:i:s', $profile['last_crawled']) }}</div>
                            </td>
                            <td class="border border-slate-300 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-200">
                                
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
    @endif
</x-filament-panels::page>
