<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight break-all text-center">
            <div class="font-semibold text-4xl">
                @if(!empty($profile->leaderboard_id) && !empty($profile->leaderboard_id))
                    <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $profile->leaderboard_id}}"
                        target="_blank">
                        {{ $profile->leaderboard_name }}
                    </a>
                @else
                    (Missing Player Name)
                @endif
            </div>
            <div class="mt-2 italic">
                {{ $hash }}
            </div>
        </h2>

    </x-slot>
    <div class="py-1">

        <x-player.menu :hash="$hash" />

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 mb-4 text-center">
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
                        <div class="p-2 border border-green-400 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-good.png') }}" /></div>
                            <div class="text-xl">Gene score</div>
                            @if(isset($scores->gene_score))
                                <div class="font-bold text-4xl text-green-400">{{ round($scores->gene_score, 2) }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-400">0</div>
                            @endif
                        </div>
                        <div class="p-2 border border-red-400 rounded-xl">
                            <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-evil.png') }}" /></div>
                            <div class="text-xl">Curse score</div>
                            @if(isset($scores->curse_score))
                                <div class="font-bold text-4xl text-red-400">{{ $scores->curse_score }}</div>
                            @else
                                <div class="font-bold text-4xl text-green-400">0</div>
                            @endif
                        </div>
                    </div>
                
            </div>

            <!-- Curses sent -->
            <div class="bg-white p-2 pt-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse" id="handle1">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle1">Curses sent by player ({{ count($sent['curses']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['curses'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Sent to:</div> 

                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold">{{ $line['leaderboard'] }} 
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No curses sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
            
            <!-- Curses recieved -->
            <div class="bg-white p-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse2" id="handle2">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle2">Curses recieved by player ({{ count($recieved['curses']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['curses'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold">
                                                    {{ $line['leaderboard'] }}
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['player_hash'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No curses recieved</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgive sent -->
            <div class="bg-white p-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse3" id="handle3">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle3">Forgives sent by player ({{ count($sent['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['forgives'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold">
                                                    {{ $line['leaderboard'] ? $line['leaderboard'] : $line['character_id'] }}
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No forgives sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgives recieved -->
            <div class="bg-white p-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse4" id="handle4">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle4">Forgives recieved by player ({{ count($recieved['forgives']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['forgives'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold">
                                                    {{ $line['leaderboard'] }}
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No forgives recieved</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Forgives all -->
            <div class="bg-white p-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse7" id="handle7">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle7">Forgiven all ({{ count($sent['all']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['all'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Sent at:</div>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No curses sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Trusts sent -->
            <div class="bg-white p-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse5" id="handle5">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle5">Trusts sent by player ({{ count($sent['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($sent['trusts'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Sent to:</div> 
                                        <a href="{{ route('player.curses', ['hash' => $line['reciever_hash'] ?? 'error']) }}" title="{{ $line['reciever_hash'] }}"> 
                                            @if(isset($line['reciever_hash']))
                                                <div class="break-all text-blue-600 font-bold">
                                                    {{ $line['leaderboard'] ?? $line['character_id'] }}
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['reciever_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
                                </div>
                            @empty
                                <span>No trusts sent</span>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <!-- Trusts recieved -->
            <div class="bg-white p-2 pb-2">
                <section class="accordion">
                    <input type="checkbox" name="collapse6" id="handle6">
                    <h2 class="handle bg-blue-400 text-white">
                        <label for="handle6">Trusts recieved by player ({{ count($recieved['trusts']) }})</label>
                    </h2>
                    <div class="content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                            @forelse($recieved['trusts'] as $line)   
                                <div class="border border-blue-400 mt-1 p-2 rounded-lg">
                                    <div>
                                        <div class="mb-2 text-md font-bold">Recieved from:</div>
                                        <a href="{{ route('player.curses', ['hash' => $line['player_hash'] ?? 'error']) }}" title="{{ $line['player_hash'] }}"> 
                                            @if(isset($line['player_hash']))
                                                <div class="break-all text-blue-600 font-bold">
                                                    {{ $line['leaderboard'] }}
                                                    <div class="text-gray-800 font-bold inline-block">
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
                                                <div class="break-all text-xs">{{ $line['player_hash'] }}</div>
                                            @else
                                                {{ $line['character_id'] }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="mt-2 text-sm">{{ $line['timestamp']->format('Y-m-d (H:i:s)') }}</div>
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
            <div class="text-center text-sm mt-2 font-gray-400">Page load time: {{ round($end, 3) }}s</div>
        </div>
    </div>
</x-app-layout>