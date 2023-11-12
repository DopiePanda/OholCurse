@php
$timezone = Auth::user()->timezone ?? 'UTC';
date_default_timezone_set($timezone);

@endphp

@section("page-title")
    - Dashboard
@endsection


<div class="w-full">
    <x-slot name="header">
        <div class="flex flex-row">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-300 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="flex-1 text-right">
                @if(Auth::user()->player_hash != null)
                    <div>
                        <a class="w-full px-4 py-2 bg-blue-400 text-white rounded-md" href="{{ route('player.curses', Auth::user()->player_hash) }}">
                            Go to my profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        
    </x-slot>

    <div class="w-full lg:w-2/3 lg:max-w-2/3 mt-6 mx-auto text-center p-2">
        <div class="flex flex-col lg:flex-row justify-center">
            <div class="flex-1 mt-2 w-full lg:w-1/2"><livewire:contacts.contact-list /></div>
            <div class="flex-1 items-end mt-2 w-full lg:w-1/2 lg:ml-2"><livewire:contacts.interaction-list /></div>
        </div>
    </div>
    <div class="mt-2 lg:py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8 py-4 border border-blue-400 rounded-lg dark:bg-slate-700 dark:border-0">
            @if(count($yumlogs) > 0)
                <div class="w-full mx-auto px-2 mt-4">
                    <div class="grid grid-cols-2">
                        <div class="text-2xl px-2 dark:text-gray-200">Curses from your Yumlog</div>
                        <div class="text-right">
                            <button class="mx-2 px-4 py-2 bg-gray-300 rounded-md" onclick="Livewire.dispatch('openModal', { component: 'modals.upload-logfile' })">
                                Upload Yumlog
                            </button>
                            <button class="mx-2 px-4 py-2 bg-green-400 text-white rounded-md" wire:click="verifyCurses()">
                                Verify curses
                            </button>
                        </div>
                    </div>
                    <div class="relative overflow-x-scroll">
                        <table class="mt-4 w-full mt-4 p-2 mx-auto border-spacing-2 border border-gray-400">
                            <thead>
                                <tr class="text-left">
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Date (UTC)</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Character ID</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Character name</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Curse name</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Status</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Verified</th>
                                    <th class="py-2 px-4 bg-blue-400 text-white dark:bg-red-500 dark:border-gray-600">Uploaded at (UTC)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($yumlogs as $log)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400" title="{{ $log->timestamp }}">
                                            {{ date('Y-m-d H:i', $log->timestamp) }}
                                        </td>
                                        <td class="p-2 border border-gray-400">{{ $log->character_id }}</td>
                                        <td class="p-2 border border-gray-400">
                                            @if($log->verified)
                                                <a href="{{ route('player.reports', ['hash' => $log->player_hash]) }}">
                                                    {{ $log->character_name }}
                                                </a>
                                            @else
                                                @if($log->character_name)
                                                    {{ $log->character_name }}
                                                @else
                                                    <i>(Name missing)</i>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="p-2 border border-gray-400">{{ $log->curse_name }}</td>
                                        <td class="p-2 border border-gray-400 text-center">
                                            @if($log->status == 0)
                                                <i title="Pending" class="text-orange-400 fa-solid fa-circle-question"></i>
                                            @elseif($log->status == 1)
                                                <i title="Verified" class="text-green-400 fa-solid fa-circle-check"></i>
                                            @elseif($log->status == 2)
                                                <i title="Archived" class="text-red-400 fa-solid fa-circle-xmark"></i>
                                            @elseif($log->status == 3)
                                                <i title="Curse-check" class="text-blue-400 fa-solid fa-magnifying-glass-minus"></i>
                                            @else
                                                <i title="Forgiven later" class="text-gray-300 fa-solid fa-clock-rotate-left"></i>
                                            @endif
                                        </td>
                                        <td class="p-2 border border-gray-400 text-center">
                                            @if($log->verified)
                                                <i title="Verified" class="text-green-400 fas fa-check-circle"></i>
                                            @else
                                                <i title="Unverified" class="text-orange-400 fas fa-question-circle"></i>
                                            @endif
                                        </td>
                                        <td class="p-2 border border-gray-400">{{ $log->created_at }}</td>
                                    </tr>
                                @empty
    
                                @endforelse
                            </tbody>
                        </table>
                    </div>   
                </div>
            @else
                <div class="w-full text-center">No yumlogs uploaded</div>
            @endif
        </div>
    </div>
</div>