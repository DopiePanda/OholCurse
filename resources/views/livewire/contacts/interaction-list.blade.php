<div class="h-full">
    <div class="w-full h-full flex flex-col items-stretch bg-white pt-4 lg:ml-2 border border-blue-400 rounded-xl dark:bg-slate-700 dark:border-0">
        
        <div class="mx-auto text-4xl mb-2 dark:text-gray-200">Latest interactions</div>
        <div class="text-md dark:text-gray-400">Incoming interactions from other players to you</div>
        <div class="w-1/3 my-4 mx-auto border-b border-gray-300 dark:border-gray-600"></div>

        @if($hash)
            <div class="flex flex-row p-2 justify-center">
                <div wire:click="getInteractions('curse')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'curse') bg-gray-200 border-2 border-blue-400 dark:bg-slate-900 dark:border-red-500 @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="fa-solid fa-book-skull fa-2x dark:text-gray-600"></i>
                    <div class="text-xs dark:text-gray-300">Curses</div>
                </div>
                <div wire:click="getInteractions('trust')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'trust') bg-gray-200 border-2 border-blue-400 dark:bg-slate-900 dark:border-red-500 @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="text-green-400 fa-solid fa-handshake-simple fa-2x"></i>
                    <div class="text-xs dark:text-gray-300">Trusts</div>
                </div>
                <div wire:click="getInteractions('forgive')" class=" h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'forgive') bg-gray-200 border-2 border-blue-400 dark:bg-slate-900 dark:border-red-500 @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="text-orange-400 fa-solid fa-heart-circle-exclamation fa-2x"></i>
                    <div class="text-xs dark:text-gray-300">Forgives</div>
                </div>
            </div>
        @else
            <div class="p-4 mx-2">
                <div class="text-lg dark:text-gray-400">To access this section you need to upload your first YumLog</div>
                <div class="mt-4">
                    <button class="mx-2 px-4 py-2 bg-blue-400 text-white rounded-md dark:bg-red-500" onclick="Livewire.emit('openModal', 'modals.upload-logfile')">
                        Upload Yumlog
                    </button>
                </div>
            </div>
        @endif

        <div class="flex flex-col flex-1 items-stretch lg:p-2">
        
        @if( $interactions)
        
            <div class="w-full mx-auto flex flex-col flex-1 overflow-x-scroll">
                @if($selected == 'curse')
                    <div class="w-full mx-2 p-4">
                        @if(count($interactions) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Character name</td>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Added</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interactions as $contact)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                    <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                                @else
                                                    {{ $contact->leaderboard_recieved->leaderboard_name ?? '(Missing leaderboard)' }}
                                                @endif
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                {{ $contact->name->name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            {{ date('Y-m-d H:i', $contact->timestamp) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic dark:text-gray-400">No curses found. That's pretty darn impressive!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'trust')
                    <div class="w-full mx-2 p-4">
                        @if(count($interactions) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Character name</td>
                                    <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Added</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interactions as $contact)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                    <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                                @else
                                                    @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->leaderboard_name != null)
                                                        {{ $contact->leaderboard_recieved->leaderboard_name }}
                                                    @else
                                                        <div class="italic uppercase">(Missing)</div>
                                                    @endif
                                                @endif
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                {{ $contact->name->name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            {{ date('Y-m-d H:i', $contact->timestamp) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic dark:text-gray-400">No trusts found. Time to go make some new friends!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'forgive')
                <div class="w-full mx-2 p-4">
                    @if(count($interactions) > 0)
                    <table class="w-full mt-4 mx-auto">
                        <thead>
                            <tr>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Leaderboard</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Character name</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-white dark:border-gray-600">Added</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interactions as $contact)
                                <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                    <td class="p-2 border border-gray-400">
                                        <a href="{{ route('player.curses', $contact->player_hash) }}">
                                            @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                            @else
                                                {{ $contact->leaderboard_recieved->leaderboard_name ?? '(Missing leaderboard name)' }}
                                            @endif
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-400">
                                        <a href="{{ route('player.curses', $contact->player_hash) }}">
                                            {{ $contact->name->name ?? 'MISSING' }}
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-400">
                                        {{ date('Y-m-d H:i', $contact->timestamp) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                            <div class="p-4 text-center text-lg italic dark:text-gray-400">No forgives recieved. Either everyone loves you, or no one does.</div>
                        @endif
                    </div>
                @endif
            </div>
            
        @endif
        <div class="w-full flex flex-row mt-auto mx-2 p-4 self-end dark:text-gray-400">
            <div class="flex-1 text-left">Total records: {{ $result_count }}</div>

            <div class="flex-1">Page: {{ ceil(($skip + $take) / $take) }} / {{ ceil($result_count / $take) }}</div>

            <div class="flex-1 text-right">
                @if($result_count > $take)
                    @if($skip > 0)
                        <div wire:click="previousPage" class="inline-block p-2 rounded-lg border border-blue-400 text-blue-400 dark:border-red-500 dark:text-red-500"><button type="button">Previous</button></div>
                    @endif
                    @if($skip + $take <= $result_count)
                        <div wire:click="nextPage" class="inline-block p-2 rounded-lg border border-blue-400 text-blue-400 dark:border-red-500 dark:text-red-500"><button type="button">Next</button></div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
