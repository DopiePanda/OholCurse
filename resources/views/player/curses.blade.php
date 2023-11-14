<x-app-layout>

    @section("page-title")
        @if( $profile )
            - Interactions for {{ $profile->leaderboard_name }}
        @else
            - Interactions
        @endif
    @endsection

    <x-slot name="header">

        <livewire:player.profile-header :hash="$hash">

    </x-slot>
    <div class="grow max-w-5xl flex flex-col">

        <x-player.menu :hash="$hash" />

        <div class="w-full mt-6 bg-gray-200 dark:bg-slate-700 p-2 lg:p-6">
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
                        <div class="p-2 bg-white dark:bg-transparent border border-green-400 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-good.png') }}" /></div>
                            <div class="text-xl dark:text-gray-400">Gene score</div>
                            @if(isset($scores->gene_score))
                                <div class="font-bold text-4xl text-green-400">{{ round($scores->gene_score, 2) }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-400">0</div>
                            @endif
                        </div>
                        <div class="p-2 bg-white dark:bg-transparent border border-red-400 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-evil.png') }}" /></div>
                            <div class="text-xl dark:text-gray-400">Curse score</div>
                            @if(isset($scores->curse_score))
                                <div class="font-bold text-4xl text-red-400">{{ $scores->curse_score }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-400">0</div>
                            @endif
                        </div>
                    </div>
                
            </div>

            <!-- Curses sent -->
            <div class="p-2 pt-2">
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse" id="handle1">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle1">Curses sent ({{ count($sent['curses']) }})</label>
                    </h2>
                    <div class="content ">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['curses'] as $line)   
                                <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold dark:text-gray-600">Sent to:</div> 

                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">

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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                                {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                                {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse2" id="handle2">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle2">Curses recieved ({{ count($recieved['curses']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['curses'] as $line)   
                            <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold dark:text-gray-600">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">

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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                            {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                            {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse3" id="handle3">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle3">Forgives sent ({{ count($sent['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['forgives'] as $line)   
                            <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold dark:text-gray-600">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">
                                                    
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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                            {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                            {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse4" id="handle4">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle4">Forgives recieved ({{ count($recieved['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['forgives'] as $line)   
                            <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold dark:text-gray-600">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">
                                                    
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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                            {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                            {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse7" id="handle7">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle7">Forgiven all ({{ count($sent['all']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['all'] as $line)   
                                <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold dark:text-gray-600">Sent at:</div>
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse5" id="handle5">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle5">Trusts sent ({{ count($sent['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['trusts'] as $line)   
                            <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold dark:text-gray-600">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">
                                                    
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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                            {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                            {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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
                <section class="accordion border border-blue-400 dark:border-red-500 rounded-lg">
                    <input type="checkbox" name="collapse6" id="handle6">
                    <h2 class="handle bg-blue-400 text-white dark:bg-transparent dark:text-red-500">
                        <label for="handle6">Trusts recieved ({{ count($recieved['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['trusts'] as $line)   
                            <div class="bg-white dark:bg-slate-800 border border-blue-400 dark:border-0 mt-1 p-2 rounded-lg">
                                <div>
                                    <div class="mb-2 text-md font-bold dark:text-gray-600">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold dark:text-red-500">
                                                    
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

                                                    <div class="text-gray-700 font-bold inline-block">
                                                        (
                                                            <span class="text-green-500">
                                                            {{ $line['gene_score'] ? $line['gene_score'] : 0; }}
                                                            </span>
                                                             / 
                                                            <span class="text-red-500">
                                                            {{ $line['curse_score'] ? $line['curse_score'] : 0; }}
                                                            </span>
                                                        )
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