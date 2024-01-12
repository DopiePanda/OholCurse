<x-app-layout>

    @section("page-title")
        @if( $profile && isset($profile->leaderboard_name) )- Interactions for {{ $profile->leaderboard_name }}@else- Interactions @endif
    @endsection

    <x-slot name="header">

        <livewire:player.profile-header :hash="$hash">

    </x-slot>
    <div class="grow max-w-5xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">

        <div class="w-full mt-6 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark p-2 lg:p-6">
            <div class="p-4 mb-4 text-center">
                <!-- <div class="text-4xl">
                    <div>
                        <div>Curse score</div>
                        @if(isset($profile->curse_score))
                            {{ $profile->curse_score }}
                        @else
                            0
                        @endif
                    </div>
                </div> -->
                
                    <div class="grid grid-cols-2 gap-4">      
                        <div class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-green-600 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-good.png') }}" /></div>
                            <div class="text-xl dark:text-gray-400">Gene score</div>
                            @if(isset($scores->gene_score))
                                <div class="font-bold text-4xl text-green-600">{{ round($scores->gene_score, 2) }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-600">0</div>
                            @endif
                        </div>
                        <div class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-red-600 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-evil.png') }}" /></div>
                            <div class="text-xl dark:text-gray-400">Curse score</div>
                            @if(isset($scores->curse_score))
                                <div class="font-bold text-4xl text-red-600">{{ $scores->curse_score }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-600">0</div>
                            @endif
                        </div>
                    </div>
                
            </div>

            <!-- Curses sent -->
            <div class="p-2 pt-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse" id="handle1">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle1">Curses sent ({{ count($sent['curses']) }})</label>
                    </h2>
                    <div class="content ">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['curses'] as $line)   
                                <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Sent to:</div> 

                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">

                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No curses sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
            
            <!-- Curses recieved -->
            <div class="p-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse2" id="handle2">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle2">Curses recieved ({{ count($recieved['curses']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['curses'] as $line)   
                            <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">

                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['player_hash'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No curses recieved</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgive sent -->
            <div class="p-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse3" id="handle3">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle3">Forgives sent ({{ count($sent['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['forgives'] as $line)   
                            <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">
                                                    
                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No forgives sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgives recieved -->
            <div class="p-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse4" id="handle4">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle4">Forgives recieved ({{ count($recieved['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['forgives'] as $line)   
                            <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">
                                                    
                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No forgives recieved</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgives all -->
            <div class="p-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse7" id="handle7">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle7">Forgiven all ({{ count($sent['all']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['all'] as $line)   
                                <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Sent at:</div>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No forgive all found</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Trusts sent -->
            <div class="p-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse5" id="handle5">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle5">Trusts sent ({{ count($sent['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['trusts'] as $line)   
                            <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">
                                                    
                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] ?? null }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No trusts sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Trusts recieved -->
            <div class="p-2 pb-2">
                <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
                    <input type="checkbox" name="collapse6" id="handle6">
                    <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                        <label for="handle6">Trusts recieved ({{ count($recieved['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['trusts'] as $line)   
                            <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="Sender was {{ $line['character_name'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">
                                                    
                                                    @if($line['contact_name'])
                                                        <span>
                                                            @if($line['contact_name']->type == 'friend')
                                                                <span class="text-green-600 dark:text-green-400">
                                                                    <i class="fas fa-heart"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @elseif($line['contact_name']->type == 'dubious')
                                                                <span class="text-orange-600 dark:text-orange-400">
                                                                    <i class="fas fa-question"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @else
                                                                <span class="text-amber-700">
                                                                    <i class="fas fa-poop"></i>
                                                                    {{ $line['contact_name']->nickname }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @elseif($line['leaderboard'])
                                                        {{  $line['leaderboard'] }}
                                                    @else
                                                        <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                            <i>{{  $line['character_name'] ?? 'Leaderboard missing' }}</i>
                                                        </span>
                                                    @endif

                                                    <div class="text-gray-700 text-sm inline-block">
                                                        (<span class="text-green-600">{{ $line['gene_score'] ? $line['gene_score'] : 0; }}</span>
                                                            /
                                                        <span class="text-red-600">{{ $line['curse_score'] ? $line['curse_score'] : 0; }}</span>)
                                                    </div>
                                                </div>
                                                <div class="break-all text-xs dark:text-gray-600">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm dark:text-gray-400">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No trusts recieved</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
            @php
            $time_end = microtime(true);
    
            $end = $time_end - $time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
        </div>
    </div>
</x-app-layout>