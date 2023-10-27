@php
$timezone = Auth::user()->timezone ?? 'UTC';
date_default_timezone_set($timezone);

@endphp

<div class="w-screen">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @if( $my_curses != null || $my_trusts != null)
        <div class="max-w-3xl mx-auto lg:grid lg:grid-cols-2 gap-4">
            @if($my_trusts != null)
                <div class="p-4 mx-auto border border-blue-400 rounded-xl">
                    <div class="text-xl text-center">Newest trusts recieved</div>
                    <table class="mt-4 mx-auto">
                        <thead>
                            <tr>
                                <td class="p-2 bg-blue-400 text-white border border-white">From</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($my_trusts as $trust)
                                <tr>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('player.curses', $trust->player_hash) }}">
                                            {{ $trust->leaderboard_recieved->leaderboard_name }}
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        {{ date('Y-m-d H:i', $trust->timestamp) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($my_curses != null)
            <div class="p-4 mx-auto border border-blue-400 rounded-xl">
                <div class="text-xl text-center">Newest curses recieved</div>
                <table class="mt-4 mx-auto">
                    <thead>
                        <tr><td class="p-2 bg-blue-400 text-white border border-white">From</td>
                            <td class="p-2 bg-blue-400 text-white border border-white">Date</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($my_curses as $curse)
                            <tr @if($curse->timestamp >= (time()-172800)) class="bg-red-200"  @else class="bg-gray-200" @endif>
                                <td class="p-2 border border-gray-300">
                                    <a href="{{ route('player.curses', $curse->player_hash) }}">
                                        {{ $curse->leaderboard_recieved->leaderboard_name }}
                                    </a> 
                                </td>
                                <td class="p-2 border border-gray-300">
                                    {{ date('Y-m-d H:i', $curse->timestamp) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif
    <div class="w-full mt-6 mx-auto text-center">
        @if(Auth::user()->player_hash != null)
            <a class="w-full mx-2 px-4 py-3 bg-blue-400 text-white rounded-md" href="{{ route('player.curses', Auth::user()->player_hash) }}">
                Go to my profile
            </a>
        @endif
    </div>
    <div class="py-12">
        <div class="max-w-2/3 mx-auto sm:px-6 lg:px-8 overflow-x-scroll">
            @if(count($yumlogs) > 0)
                <div class="w-2/4 mx-auto mt-4 overflow-x-scroll">
                    <div class="grid grid-cols-2">
                        <div class="text-2xl">Curses from your Yumlog</div>
                        <div class="text-right">
                            <button class="mx-2 px-4 py-2 bg-gray-300 rounded-md" onclick="Livewire.emit('openModal', 'modals.upload-logfile')">
                                Upload Yumlog
                            </button>
                            <button class="mx-2 px-4 py-2 bg-green-400 text-white rounded-md" wire:click="verifyCurses()">
                                Verify curses
                            </button>
                        </div>
                    </div>
                    
                    <table class="mt-4 max-w-screen mt-4 p-2 mx-auto border-spacing-2 border border-gray-400">
                        <thead>
                            <tr class="text-left">
                                <th class="py-2 px-4 bg-blue-400 text-white">Date (UTC)</th>
                                <th class="py-2 px-4 bg-blue-400 text-white">Character ID</th>
                                <th class="py-2 px-4 bg-blue-400 text-white">Character name</th>
                                <th class="py-2 px-4 bg-blue-400 text-white">Curse name</th>
                                <th class="py-2 px-4 bg-blue-400 text-white">Verified</th>
                                <th class="py-2 px-4 bg-blue-400 text-white">Uploaded at (UTC)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($yumlogs as $log)
                                <tr>
                                    <td class="p-2 border border-gray-200" title="{{ $log->timestamp }}">
                                        {{ date('Y-m-d H:i', $log->timestamp) }}
                                    </td>
                                    <td class="p-2 border border-gray-200">{{ $log->character_id }}</td>
                                    <td class="p-2 border border-gray-200">
                                        @if($log->verified)
                                            <a href="{{ route('player.curses', ['hash' => $log->player_hash]) }}">
                                                {{ $log->character_name }}
                                            </a>
                                        @else
                                            {{ $log->character_name }}
                                        @endif
                                    </td>
                                    <td class="p-2 border border-gray-200">{{ $log->curse_name }}</td>
                                    <td class="p-2 border border-gray-200 text-center">
                                        @if($log->verified)
                                            <i title="Verified" class="text-green-400 fas fa-check-circle"></i>
                                        @else
                                            <i title="Unverified" class="text-orange-300 fas fa-question-circle"></i>
                                        @endif
                                    </td>
                                    <td class="p-2 border border-gray-200">{{ $log->created_at }}</td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>