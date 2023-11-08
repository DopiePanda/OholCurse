<div>
    <div class="w-full bg-white mr-2 py-4 border border-blue-400 rounded-xl">

        <div class="mx-auto text-4xl mb-2">Contact list</div>
        <div class="text-md">Profiles saved to your account are private and non-public</div>
        <div class="w-2/3 my-4 mx-auto border-b border-gray-300"></div>

        <div class="flex flex-row p-2 justify-center text-center">
            <div wire:click="getContacts('friend')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'friend') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                <i class="text-rose-500 fas fa-heart fa-2x"></i>
                <div class="text-xs">Friends</div>
            </div>
            <div wire:click="getContacts('dubious')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'dubious') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                <i class="text-orange-500 fas fa-question fa-2x"></i>
                <div class="w-full text-xs">Uncertains</div>
            </div>
            <div wire:click="getContacts('enemy')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'enemy') bg-gray-200 border-2 border-blue-400 @else bg-gray-200 @endif">
                <i class="text-yellow-950 fas fa-poop fa-2x"></i>
                <div class="text-xs">Enemies</div>
            </div>
        </div>

        @if( $contacts)
            <div class="w-full mx-auto flex flex-row justify-center items-top text-center overflow-x-scroll">
                @if($selected == 'friend')
                    <div class="w-full mx-2 p-4">
                        @if(count($contacts) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Nickname</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Phex hash</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td class="text-center p-2 border border-gray-300">
                                            <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                {{ $contact->nickname }}
                                            </button> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->player->leaderboard_name }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->phex_hash ?? 'N/A' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $contact->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="border border-gray-300">
                                            <div class="inline-block">
                                                <button class="inline-block" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                    <i class="text-blue-400 fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </div>
                                            <div class="inline-block">
                                                <i wire:click="delete({{ $contact->id }})" wire:confirm="Are you sure you want to remove this contact?" class="inline-block ml-2 text-red-400 fa-solid fa-trash-can"></i>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic">No friends found. Add some now from their player profiles!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'dubious')
                    <div class="w-full mx-2 p-4">
                        @if(count($contacts) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Nickname</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Phex hash</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                                    <td class="p-2 bg-blue-400 text-white border border-white">Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td class="text-center p-2 border border-gray-300">
                                            <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                {{ $contact->nickname }}
                                            </button> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->player->leaderboard_name }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->phex_hash ?? 'N/A' }}
                                            </a> 
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $contact->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="border border-gray-300">
                                            <div class="inline-block">
                                                <button class="inline-block" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                    <i class="text-blue-400 fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </div>
                                            <div class="inline-block">
                                                <i wire:click="delete({{ $contact->id }})" wire:confirm="Are you sure you want to remove this contact?" class="inline-block ml-2 text-red-400 fa-solid fa-trash-can"></i>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-4 text-center text-lg italic">No unknown contacts, you really know your stuff!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'enemy')
                <div class="w-full mx-2 p-4">
                    @if(count($contacts) > 0)
                    <table class="w-full mt-4 mx-auto">
                        <thead>
                            <tr>
                                <td class="p-2 bg-blue-400 text-white border border-white">Nickname</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Leaderboard</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Phex hash</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Added</td>
                                <td class="p-2 bg-blue-400 text-white border border-white">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td class="text-center p-2 border border-gray-300">
                                        <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                            {{ $contact->nickname }}
                                        </button> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('player.curses', $contact->hash) }}">
                                            {{ $contact->player->leaderboard_name }}
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('player.curses', $contact->hash) }}">
                                            {{ $contact->phex_hash ?? 'N/A' }}
                                        </a> 
                                    </td>
                                    <td class="p-2 border border-gray-300">
                                        {{ $contact->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="border border-gray-300">
                                        <div class="inline-block">
                                            <button class="inline-block" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                <i class="text-blue-400 fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </div>
                                        <div class="inline-block">
                                            <i wire:click="delete({{ $contact->id }})" wire:confirm="Are you sure you want to remove this contact?" class="inline-block ml-2 text-red-400 fa-solid fa-trash-can"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                            <div class="p-4 text-center text-lg italic">No enemies? That's pretty impressive!</div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="mx-2 p-4 flex items-stretch">
                <div class="flex-1 text-left">Total records: {{ $result_count }}</div>

                <div class="flex-1">
                    @if(ceil($result_count / $take) > 0)
                        Page: {{ ceil(($skip + $take) / $take) }} / {{ ceil($result_count / $take) }}
                    @endif
                </div>

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
