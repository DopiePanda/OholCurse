<div class="w-full md:w-3/4 lg:w-3/5">

    <x-effects.backgrounds.animated-background :donator="$donator" wire:ignore />
    
    @section("page-title")
    @if( $profile && isset($profile->leaderboard_name) )
    - Statistics for {{ $profile->leaderboard_name }}
    @else
    - Statistics 
    @endif
    @endsection

    <x-slot name="header">

        <livewire:player.profile-header :hash="$hash" :donator="$donator">

    </x-slot>
    <div class="grow w-full min-w-7xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">

        <div class="w-full mt-6 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark p-2 lg:p-6">
            <div class="mb-4 text-4xl text-center text-skin-base dark:text-skin-base-dark">General player statistics</div>
            <div class="p-2 mb-2 text-center">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">      
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ round($hours_played, 1) ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Hours played</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Hours played to life Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">{{ round(($hours_played ?? 0) / $all_lives, 2) }}</div>
                    </div>
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ $children_born ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Children born</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Childred to life Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">{{ round(($children_born ?? 0) / $all_lives, 2) }}</div>
                    </div>
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ $foods_eaten ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Food items eaten</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Food to life Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">{{ round(($foods_eaten ?? 0) / $food_lives, 2) }}</div>
                    </div>
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ $eve_lives ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Eve lives</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Eve to Life Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">{{ round(($eve_lives ?? 0) / $all_lives, 4) }}</div>
                    </div>
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ $ghost_lives['filtered'] ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Ghost lives</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Ghost to life Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">{{ round(($ghost_lives['filtered'] ?? 0) / $food_lives, 4) }}</div>
                    </div>
                    <div class="px-4 py-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-3xl md:text-4xl lg:text-5xl text-skin-base dark:text-skin-base-dark">{{ $times_killed ?? 0 }}</div>
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Times murdered</div>
                        @if(isset($times_killed) && isset($player_kills))
                        <div class="mt-2 uppercase text-sm text-gray-800 dark:text-gray-400">Kill to death Ratio:</div>
                        <div class="mt-2 uppercase text-sm text-skin-base dark:text-skin-base-dark">
                                {{ round(($times_killed ?? 0) / ($player_kills ?? 0), 4) }}  
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 mb-4 text-center">
                <div class="mb-4 text-4xl text-center text-skin-base dark:text-skin-base-dark">Names recieved from parent</div>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">      
                    <div class="p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-2xl text-skin-base dark:text-skin-base-dark">Top female names recieved</div>
                        @forelse ($female_names_recieved as $name)
                            <div class="mt-2 p-2 flex flex-row text-gray-800 dark:text-white">
                                <div class="basis-1/4">#{{ $loop->index+1 }}</div>
                                <div class="basis-1/2">{{ $name['name'] }}</div>
                                <div class="basis-1/4">{{ $name['count'] }} {{ $name['count'] > 1 ? 'lives' : 'life' }}</div>
                            </div>
                            @if( !$loop->last)
                            <div class="w-3/4 h-1 border-b mx-auto border-dotted border-gray-400 dark:border-gray-800"></div> 
                            @endif
                        @empty
                            
                        @endforelse
                    </div>
                    <div class="p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-2xl text-skin-base dark:text-skin-base-dark">Top male names recieved</div>
                        @forelse ($male_names_recieved as $name)
                            <div class="mt-2 p-2 flex flex-row text-gray-800 dark:text-white">
                                <div class="basis-1/4">#{{ $loop->index+1 }}</div>
                                <div class="basis-1/2">{{ $name['name'] }}</div>
                                <div class="basis-1/4">{{ $name['count'] }} {{ $name['count'] > 1 ? 'lives' : 'life' }}</div>
                            </div>
                            @if( !$loop->last)
                            <div class="w-3/4 h-1 border-b mx-auto border-dotted border-gray-400 dark:border-gray-800"></div> 
                            @endif
                        @empty
                            
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 mb-4 text-center">
                <div class="mb-4 text-4xl text-center text-skin-base dark:text-skin-base-dark">Names given to own children</div>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">      
                    <div class="p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-2xl text-skin-base dark:text-skin-base-dark">Top female names given</div>
                        @forelse ($female_names_given as $name)
                            <div class="mt-2 p-2 flex flex-row text-gray-800 dark:text-white">
                                <div class="basis-1/4">#{{ $loop->index+1 }}</div>
                                <div class="basis-1/2">{{ $name['name'] }}</div>
                                <div class="basis-1/4">{{ $name['count'] }} {{ $name['count'] > 1 ? 'lives' : 'life' }}</div>
                            </div>
                            @if( !$loop->last)
                            <div class="w-3/4 h-1 border-b mx-auto border-dotted border-gray-400 dark:border-gray-800"></div> 
                            @endif
                        @empty
                            
                        @endforelse
                    </div>
                    <div class="p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
                        <div class="text-2xl text-skin-base dark:text-skin-base-dark">Top male names given</div>
                        @forelse ($male_names_given as $name)
                            <div class="mt-2 p-2 flex flex-row text-gray-800 dark:text-white">
                                <div class="basis-1/4">#{{ $loop->index+1 }}</div>
                                <div class="basis-1/2">{{ $name['name'] }}</div>
                                <div class="basis-1/4">{{ $name['count'] }} {{ $name['count'] > 1 ? 'lives' : 'life' }}</div>
                            </div>
                            @if( !$loop->last)
                            <div class="w-3/4 h-1 border-b mx-auto border-dotted border-gray-400 dark:border-gray-800"></div> 
                            @endif
                        @empty
                            
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 mb-4">
                <div class="text-4xl text-center text-skin-base dark:text-skin-base-dark">Top 25 favorite food items</div>
                <div class="relative my-6 pb-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-auto">
                    <table class="w-full table-fixed text-left">
                        <thead>
                            <tr class="text-left">
                                <th class="w-1/3 text-center p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Placement</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Item</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Amount</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Life Ratio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($top_foods_eaten as $key => $value)
                                <tr class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark dark:text-gray-300">
                                    <td class="p-2 text-center border-b border-gray-400 dark:border-gray-800">#{{ $loop->index+1 }}</td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ explode('#', $key)[0] }}</td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ count($value) }}</td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ round(count($value) / $food_lives, 2) }}</td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 mb-4">
                <div class="text-4xl text-center text-skin-base dark:text-skin-base-dark">Causes of death</div>
                <div class="relative my-6 pb-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-auto">
                    <table class="w-full table-fixed text-left">
                        <thead>
                            <tr class="text-left">
                                <th class="w-1/3 text-center p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Placement</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Cause</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Count</th>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($death_causes as $key => $value)
                                <tr 
                                    @if($key == 'killed') 
                                        wire:click="$dispatch('openModal', {component: 'player.component.modal-killers', arguments: {killers: {{ collect($value) }}, hash: '{{ $hash }}' }})"
                                        class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark dark:text-gray-300 hover:bg-skin-fill-wrapper hover:dark:bg-skin-fill-wrapper-dark cursor-pointer"
                                    @else
                                        class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark dark:text-gray-300"
                                    @endif
                                >
                                    <td class="p-2 text-center border-b border-gray-400 dark:border-gray-800">#{{ $loop->index+1 }}</td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">
                                        {{ ucfirst(str_replace('dA', 'd A', str_replace('_', ' ', $key))) }}
                                        @if($key == 'killed')
                                            <i class="ml-1 text-skin-muted dark:text-skin-muted-dark fa-solid fa-angles-right fa-2xs"></i>
                                        @endif
                                    </td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ count($value) }}/{{ $total_lives }}</td>
                                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ round(count($value) / $total_lives * 100, 1) }}%</td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            @php
            $time_end = microtime(true);
    
            $end = $time_end - $start_time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-800 dark:text-gray-400">Page load time: {{ round($end, 3) }}s</div>

        </div>
</div>
