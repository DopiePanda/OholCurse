<div class="lg:h-full">
    <div class="w-full h-full flex flex-col items-stretch pt-4 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border rounded-xl border-skin-base dark:border-skin-base-dark">

        <div class="mx-auto text-4xl mb-2 text-skin-base dark:text-skin-base-dark">Contact list</div>
        <div class="text-center text-md text-skin-muted dark:text-skin-muted-dark">Profiles saved to your account are private and non-public</div>
        <div class="w-1/3 my-4 mx-auto border-b border-gray-300 dark:border-gray-600"></div>

        <div class="flex flex-row p-2 justify-center text-center">
            <div wire:click="getContacts('friend')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'friend') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                <i class="text-rose-500 fas fa-heart fa-2x"></i>
                <div class="text-xs dark:text-gray-300">Friends</div>
            </div>
            <div wire:click="getContacts('dubious')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'dubious') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                <i class="text-orange-500 fas fa-question fa-2x"></i>
                <div class="w-full text-xs dark:text-gray-300">Uncertains</div>
            </div>
            <div wire:click="getContacts('enemy')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'enemy') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                <i class="text-yellow-950 fas fa-poop fa-2x"></i>
                <div class="text-xs dark:text-gray-300">Enemies</div>
            </div>
        </div>

        <div class="flex flex-col flex-1 items-stretch lg:p-2">

        @if( $contacts)
            <div class="w-full mx-auto flex flex-col flex-1 overflow-x-scroll">
                @if($selected == 'friend')
                    <div class="w-full p-4">
                        @if(count($contacts) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Leaderboard</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Nickname</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Comment</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Phex hash</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->player->leaderboard_name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="text-center p-2 border border-gray-400">
                                            <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                {{ $contact->nickname }}
                                            </button> 
                                        </td>
                                        <td class="text-center p-2 border border-gray-400" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                            @if($contact->comment != null)
                                                <div class="text-left mx-2" >
                                                    {{ Str::of($contact->comment)->limit(256, ' ...') }}
                                                </div> 
                                            @else
                                                <span class="italic">No comment added yet. Click here to add</span>
                                            @endif
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->phex_hash ?? 'N/A' }}
                                            </a> 
                                        </td>
                                        <td class="border border-gray-400">
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
                            <div class="p-4 text-center text-lg italic dark:text-gray-400">No friends found. Add some now from their player profiles!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'dubious')
                    <div class="w-full mx-2 p-4">
                        @if(count($contacts) > 0)
                        <table class="w-full mt-4 mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Leaderboard</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Nickname</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Comment</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Phex hash</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->player->leaderboard_name ?? 'MISSING' }}
                                            </a> 
                                        </td>
                                        <td class="text-center p-2 border border-gray-400">
                                            <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                                {{ $contact->nickname }}
                                            </button> 
                                        </td>
                                        <td class="text-center p-2 border border-gray-400" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                            @if($contact->comment != null)
                                                <button class="mx-2" >
                                                    {{ $contact->comment }}
                                                </button> 
                                            @else
                                                <span class="italic">No comment added yet. Click here to add</span>
                                            @endif
                                        </td>
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->hash) }}">
                                                {{ $contact->phex_hash ?? 'N/A' }}
                                            </a> 
                                        </td>
                                        <td class="border border-gray-400">
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
                            <div class="p-4 text-center text-lg italic dark:text-gray-400">No unknown contacts, you really know your stuff!</div>
                        @endif
                    </div>
                @endif

                @if($selected == 'enemy')
                <div class="w-full mx-2 p-4">
                    @if(count($contacts) > 0)
                    <table class="w-full mt-4 mx-auto">
                        <thead>
                            <tr>
                                <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Leaderboard</td>
                                <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Nickname</td>
                                <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Comment</td>
                                <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Phex hash</td>
                                <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                    <td class="p-2 border border-gray-400">
                                        <a href="{{ route('player.curses', $contact->hash) }}">
                                            {{ $contact->player->leaderboard_name ?? 'MISSING' }}
                                        </a> 
                                    </td>
                                    <td class="text-center p-2 border border-gray-400">
                                        <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                            {{ $contact->nickname }}
                                        </button> 
                                    </td>
                                    <td class="text-center p-2 border border-gray-400" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$contact->hash}}'}})">
                                        @if($contact->comment != null)
                                            <button class="mx-2" >
                                                {{ $contact->comment }}
                                            </button> 
                                        @else
                                            <span class="italic">No comment added yet. Click here to add</span>
                                        @endif
                                    </td>
                                    <td class="p-2 border border-gray-400">
                                        <a href="{{ route('player.curses', $contact->hash) }}">
                                            {{ $contact->phex_hash ?? 'N/A' }}
                                        </a> 
                                    </td>
                                    <td class="border border-gray-400">
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
                        <div class="p-4 text-center text-lg italic dark:text-gray-400">No enemies? That's pretty impressive!</div>
                    @endif
                </div>
            @endif
        </div>
        @endif
        <div class="w-full flex flex-row mt-auto mx-2 p-4 self-end text-skin-muted dark:text-skin-muted-dark">
            <div class="flex-1 text-left">Total records: {{ $result_count }}</div>

            <div class="flex-1">
                @if($result_count && ceil($result_count / $take) > 0)
                    Page: {{ ceil(($skip + $take) / $take) }} / {{ ceil($result_count / $take) }}
                @endif
            </div>

            <div class="flex-1 text-right">
                @if($result_count > $take)
                    @if($skip > 0)
                        <div wire:click="previousPage" class="inline-block p-2 rounded-lg text-white bg-skin-base dark:bg-skin-fill-dark"><button type="button">Previous</button></div>
                    @endif
                    @if($skip + $take <= $result_count)
                        <div wire:click="nextPage" class="inline-block p-2 rounded-lg text-white bg-skin-base dark:bg-skin-fill-dark"><button type="button">Next</button></div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
