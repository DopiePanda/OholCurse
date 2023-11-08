<div>
    <div class="w-full bg-white py-4 lg:ml-2 border border-blue-400 rounded-xl">
        
        <div class="mx-auto text-4xl mb-2">Latest interactions</div>
        <div class="text-md">Incoming interactions from other players to you</div>
        <div class="w-2/3 my-4 mx-auto border-b border-gray-300"></div>

        @if($hash)
            <div class="flex flex-row p-2 justify-center">
                <div wire:click="getInteractions('curse')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'curse') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                    <i class="fa-solid fa-book-skull fa-2x"></i>
                    <div class="text-xs">Curses</div>
                </div>
                <div wire:click="getInteractions('trust')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'trust') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                    <i class="text-green-400 fa-solid fa-handshake-simple fa-2x"></i>
                    <div class="text-xs">Trusts</div>
                </div>
                <div wire:click="getInteractions('forgive')" class=" h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'forgive') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                    <i class="text-orange-400 fa-solid fa-heart-circle-exclamation fa-2x"></i>
                    <div class="text-xs">Forgives</div>
                </div>
            </div>
        @else
            <div class="p-4 mx-2">
                <div class="text-lg">To access this section you need to upload your first YumLog</div>
                <div class="mt-4">
                    <button class="mx-2 px-4 py-2 bg-blue-400 text-white rounded-md" onclick="Livewire.emit('openModal', 'modals.upload-logfile')">
                        Upload Yumlog
                    </button>
                </div>
            </div>
        @endif
        
        @if( $interactions)
        
            <div class="w-full mx-auto flex flex-row justify-center items-top overflow-x-scroll">
                @if($selected == 'curse')
                    <div class="w-full mx-2 p-4">
                        @if(count($interactions) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Character name</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interactions as $contact)
                                    <tr>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                    <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                                @else
                                                    {{ $contact->leaderboard_recieved->leaderboard_name ?? '(Missing leaderboard)' }}
                                                @endif
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                {{ $contact->name->name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ date('Y-m-d H:i', $contact->timestamp) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic">No curses found. That's pretty darn impressive!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'trust')
                    <div class="w-full mx-2 p-4">
                        @if(count($interactions) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Character name</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interactions as $contact)
                                    <tr>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                    <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                                @else
                                                    {{ $contact->leaderboard_recieved->leaderboard_name ?? '(Missing leaderboard name)' }}
                                                @endif
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                {{ $contact->name->name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ date('Y-m-d H:i', $contact->timestamp) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic">No trusts found. Time to go make some new friends!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'forgive')
                <div class="w-full mx-2 p-4">
                    @if(count($interactions) > 0)
                    <table class="w-full mt-4 mx-auto">
                        <thead>
                            <tr>
                                <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Character name</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interactions as $contact)
                                <tr>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('player.curses', $contact->player_hash) }}">
                                            @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                <b>{{ $contact->leaderboard_recieved->contact->nickname}}</b>
                                            @else
                                                {{ $contact->leaderboard_recieved->leaderboard_name ?? '(Missing leaderboard name)' }}
                                            @endif
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('player.curses', $contact->player_hash) }}">
                                            {{ $contact->name->name ?? 'MISSING' }}
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        {{ date('Y-m-d H:i', $contact->timestamp) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                            <div class="p-4 text-center text-lg italic">No forgives recieved. Either everyone loves you, or no one does.</div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="mx-2 p-4 flex items-stretch">
                <div class="flex-1 text-left">Total records: {{ $result_count }}</div>

                <div class="flex-1">Page: {{ ceil(($skip + $take) / $take) }} / {{ ceil($result_count / $take) }}</div>

                <div class="flex-1 text-right">
                    @if($result_count > $take)
                        @if($skip > 0)
                            <div wire:click="previousPage" class="inline-block"><button type="button">Previous</button></div>
                        @endif
                        @if($skip + $take <= $result_count)
                            <div wire:click="nextPage" class="inline-block"><button type="button">Next</button></div>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
