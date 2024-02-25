@php
$timezone = Auth::user()->timezone ?? 'UTC';
date_default_timezone_set($timezone);

@endphp

@section("page-title")- Dashboard @endsection


<div class="w-full lg:max-w-2/3">
    <x-slot name="header">
        <div class="flex flex-row">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-skin-base dark:text-skin-base-dark leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="flex-1 text-right">
                @if(Auth::user()->player_hash != null)
                    <div>
                        <a class="w-full px-4 py-2 text-white bg-skin-fill dark:bg-skin-fill-dark rounded-md" href="{{ route('player.interactions', Auth::user()->player_hash) }}">
                            Go to my profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        
    </x-slot>

    <div class="w-1/2 mx-auto flex flex-row gap-4 items-center justify-center">
        <div wire:click="setTab('contacts')" class="px-4 py-2 text-white border border-skin-base dark:border-skin-base-dark rounded-lg cursor-pointer @if($tab == 'contacts') bg-skin-fill @endif">
            Contacts
        </div>
        <div wire:click="setTab('interactions')" class="px-4 py-2 text-white border border-skin-base dark:border-skin-base-dark rounded-lg cursor-pointer @if($tab == 'interactions') bg-skin-fill @endif">
            Interactions
        </div>
        <div wire:click="setTab('reports')" class="px-4 py-2 text-white border border-skin-base dark:border-skin-base-dark rounded-lg cursor-pointer @if($tab == 'reports') bg-skin-fill @endif">
            Reports
        </div>
    </div>

    <div class="mt-4 w-full lg:w-1/2 mx-auto">

        @if($tab == 'contacts')
            <livewire:contacts.contact-list />
        @elseif($tab == 'interactions')
            <livewire:contacts.interaction-list />
        @elseif($tab == 'reports')
            @if(count($yumlogs) > 0)

                <div class="w-full mx-auto py-4 px-6 mt-4 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border rounded-xl border-skin-base dark:border-skin-base-dark">
                    <div class="text-center mx-auto text-4xl mb-2 text-skin-base dark:text-skin-base-dark">Yumlogs</div>
                    <div class="text-md text-center text-skin-muted dark:text-skin-muted-dark">Yours submitted yumlog curse reports</div>
                    <div class="w-1/3 my-4 mx-auto border-b border-gray-300 dark:border-gray-600"></div>
                    <div class="flex flex-row justify-center gap-4">
                        <div class="mx-2 px-4 py-2 bg-gray-300 rounded-md cursor-pointer" onclick="Livewire.dispatch('openModal', { component: 'modals.upload-logfile' })">
                            Upload Yumlog
                        </div>
                        <div class="mx-2 px-4 py-2 bg-green-400 text-white rounded-md cursor-pointer" wire:click="verifyCurses()">
                            Verify curses
                        </div>
                    </div>
                    <div class="relative overflow-x-scroll">
                        <table class="mt-4 w-full mt-4 p-2 mx-auto border-spacing-2 border border-gray-400">
                            <thead>
                                <tr class="text-left">
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Date (UTC)</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Character ID</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Character name</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Curse name</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Status</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Verified</th>
                                    <th class="py-2 px-4 text-white bg-skin-fill dark:bg-skin-fill-dark border-r dark:border-gray-600">Uploaded at (UTC)</th>
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
                                            @elseif($log->status == 4)
                                                <i title="Forgiven later" class="text-blue-400 fa-solid fa-magnifying-glass-minus"></i>
                                            @else
                                                <i title="Other" class="text-purple-400 fa-solid fa-question"></i>
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
                    <div class="mt-4 grid grid-cols-2">
                        <div class="text-left">
                            <span wire:click="previousPage()" class="cursor-pointer w-auto p-2 text-white bg-skin-base dark:bg-skin-fill-dark rounded-lg">
                                Previous
                            </span>
                        </div>
                        <div class="text-right">
                            <span wire:click="nextPage()" class="cursor-pointer w-auto p-2 text-white bg-skin-base dark:bg-skin-fill-dark rounded-lg">
                                Next
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="w-full text-center text-gray-800 dark:text-gray-400">No yumlogs uploaded</div>
                <div>
                    <button class="mt-2 mx-2 px-4 py-2 bg-gray-300 rounded-md" onclick="Livewire.dispatch('openModal', { component: 'modals.upload-logfile' })">
                        Upload Yumlog
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>