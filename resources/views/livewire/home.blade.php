<div class="lg:w-2/6">
    @section("page-title")- Search OHOL profiles @endsection
    <div class="flex flex-col items-center">
        <img class="w-96" src="{{ asset('assets/uploads/images/new-logo-transparent.png') }}" alt="oholcurse-logo" />
       
        <div class="w-screen lg:w-5/6">
            <div class="mt-8 w-full text-center uppercase text-sm font-bold dark:text-gray-400">Filter search by:</div>
            <div class="mt-2 w-full text-center">
                <div class="w-11/12 mx-auto lg:w-full flex flex-row items-center text-xs md:text-sm border border-gray-600 dark:border-slate-600 rounded-lg">
                    <button type="button" class="grow rounded-l-lg row py-2 px-4 @if($filter == 'character_name') bg-skin-fill dark:bg-skin-fill-dark text-white font-semibold @else bg-gray-200 dark:bg-slate-600 dark:text-gray-300 @endif" wire:click="setSearchFilter('character_name')">
                        Character name
                    </button>
                    <button type="button" class="grow py-2 px-4 border-l border-r border-gray-400 dark:border-gray-500 @if($filter == 'curse_name') bg-skin-fill dark:bg-skin-fill-dark text-white font-semibold @else bg-gray-200 dark:bg-slate-600 dark:text-gray-300 @endif" wire:click="setSearchFilter('curse_name')">
                        Curse name
                    </button>
                    <button type="button" class="grow py-2 px-4 border-r border-gray-400 dark:border-gray-500 @if($filter == 'leaderboard') bg-skin-fill dark:bg-skin-fill-dark text-white font-semibold @else bg-gray-200 dark:bg-slate-600 dark:text-gray-300 @endif" wire:click="setSearchFilter('leaderboard')">
                        Leaderboard name
                    </button>
                    <button type="button" class="grow rounded-r-lg py-2 px-4 @if($filter == 'player_hash') bg-skin-fill dark:bg-skin-fill-dark text-white font-semibold @else bg-gray-200 dark:bg-slate-600 dark:text-gray-300 @endif" wire:click="setSearchFilter('player_hash')">
                        Player hash
                    </button>
                </div>
            </div>
            <div class="w-full text-center">
                <input type="text" wire:model.live="query" wire:keyup.debounce.150ms="search" class="mx-auto mt-2 w-11/12 lg:w-full h-14 dark:bg-slate-500 dark:text-gray-800 dark:placeholder:text-gray-700 @if(strlen($query) >= $minQueryLength) rounded-t-lg @else rounded-lg @endif"

                @if($filter == 'character_name') placeholder="Search by typing a character name here..." @endif
                @if($filter == 'curse_name') placeholder="Search by typing a curse name here..." @endif
                @if($filter == 'leaderboard') placeholder="Search by typing a leaderboard name here..." @endif
                @if($filter == 'player_hash') placeholder="Search by typing a player hash here..." @endif
                >
            </div>
           

            @if(strlen($query) >= $minQueryLength)
            <div class="w-11/12 lg:w-full mx-auto">
                @forelse($results as $result)
                    <div wire:key="id-{{ $result['id'] }}">
                        @if ($filter == 'character_name')
                            <a href="{{ route('player.interactions', [ 'hash' => $result['character']['player_hash'] ?? 'missing' ]) }}" class="text-white">
                        @else
                            <a href="{{ route('player.interactions', [ 'hash' => $result['player_hash'] ?? 'missing' ]) }}" class="text-white">
                        @endif
                        
                            <div class="w-full bg-skin-fill dark:bg-skin-fill-dark text-white p-3 text-md hover:bg-skin-fill-hover hover:dark:bg-skin-fill-hover-dark">
                                @if ($filter == 'character_name')
                                    <!-- CHARACTER NAME -->
                                    @if(isset($result['name']))
                                        {{ $result['name'] }}
                                    @endif 

                                    @if(isset($result['character']['timestamp'])) 
                                        ({{ gmdate('Y-m-d H:i:s', $result['character']['timestamp']) }}) 
                                    @endif

                                @elseif ($filter == 'curse_name')
                                    <!-- CURSE NAME -->
                                    @if(isset($result['curse_name']))
                                        {{ $result['curse_name'] }}
                                    @endif 

                                    @if(isset($result['timestamp'])) 
                                        ({{ gmdate('Y-m-d H:i:s', $result['timestamp']) }}) 
                                    @endif

                                @elseif ($filter == 'leaderboard')
                                    <!-- LEADERBOARD NAME -->
                                    @if(isset($result['leaderboard_name']))
                                        {{ $result['leaderboard_name'] }}
                                    @endif 

                                    @if(isset($result['leaderboard_id'])) 
                                        (ID: {{ $result['leaderboard_id'] }}) 
                                    @endif

                                @else
                                    <!-- PLAYER HASH -->
                                    {{ strtolower($result['player_hash'] ?? 'missing') }}

                                @endif
                                
                            </div>
                        </a>
                    </div>
                @empty
                    @if($query && $query != '')
                        <div class="w-full bg-skin-fill-hover dark:bg-skin-fill-hover-dark text-white p-3 text-md hover:bg-skin-fill-hover hover:dark:bg-skin-fill-hover-dark">
                            <span class="text-sm italic">No results found..</span>
                        </div>
                    @endif
                @endforelse

                @if($count > count($results))
                    <div class="flex flex-row justify-between">
                        <div class="w-16 min-w-16 cursor-pointer bg-skin-fill dark:bg-skin-fill-dark hover:bg-skin-fill-hover hover:dark:bg-skin-fill-hover-dark">
                            @if($fetchCursor >= $fetchLimit)
                                <button class="block py-2 px-6 w-full h-full text-white font-bold text-xl" type="button" wire:click="decreaseCursor()"><</button>
                            @endif
                        </div>
                        <div class="pt-2 bg-transparent text-skin-base dark:text-skin-base-dark">
                            <div>Page: {{ ($fetchCursor / 10) + ($fetchLimit / 10) }} / {{ ceil($count/10) }}</div>
                        </div>
                        <div class="w-16 min-w-16 cursor-pointer bg-skin-fill dark:bg-skin-fill-dark hover:bg-skin-fill-hover hover:dark:bg-skin-fill-hover-dark">
                            @if(($fetchCursor + $fetchLimit) < $count)
                                <button class="block py-2 px-6 w-full h-full text-white font-bold text-xl" type="button" wire:click="increaseCursor()">></button>
                            @endif
                        </div>
                    </div>
                @endif
                
            </div>
            @endif
        </div>
        <div class="w-screen lg:w-96 text-center mt-4 italic text-sm px-4 py-1 rounded-lg">
            <span class="font-bold text-orange-500">Note: </span><span class="text-black dark:text-gray-400">Current earliest data entry is from: <br/>2023-07-13 and newest from: {{ now()->subDays(1)->format('Y-m-d') }}</span>
        </div>
    </div>
</div>