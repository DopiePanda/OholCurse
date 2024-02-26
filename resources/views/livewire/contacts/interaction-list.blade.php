<div class="h-full">
    <div class="w-full h-full flex flex-col items-stretch py-4 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border rounded-xl border-skin-base dark:border-skin-base-dark">
        
        <div class="mx-auto text-4xl mb-2 text-skin-base dark:text-skin-base-dark">Latest interactions</div>
        <div class="text-md text-center text-skin-muted dark:text-skin-muted-dark">Incoming interactions from other players to you</div>
        <div class="w-1/3 my-4 mx-auto border-b border-gray-300 dark:border-gray-600"></div>

        @if(!$hash)
            <div class="w-3/4 flex flex-col gap-4 text-center px-2 py-8 mx-auto rounded-lg bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                <div>
                    <i class="fa-solid fa-lock fa-4x text-skin-muted dark:text-skin-muted-dark"></i>
                </div>
                <div class="text-lg dark:text-gray-400">To access this section you need to upload your first YumLog</div>
                <div class="mt-4">
                    <span class="p-4 text-white bg-skin-fill dark:bg-skin-fill-dark cursor-pointer" onclick="Livewire.dispatch('openModal', { component: 'modals.upload-logfile' })">
                        Upload Yumlog
                    </span>
                </div>
            </div>
        @else

            <!-- Interaction type selector -->

            <div class="flex flex-row p-2 justify-center text-center">
                <div wire:click="getInteractions('curse')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'curse') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="fa-solid fa-book-skull fa-2x dark:text-gray-600"></i>
                    <div class="text-xs dark:text-gray-300">Curses</div>
                </div>
                <div wire:click="getInteractions('trust')" class="h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'trust') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="text-green-400 fa-solid fa-handshake-simple fa-2x"></i>
                    <div class="text-xs dark:text-gray-300">Trusts</div>
                </div>
                <div wire:click="getInteractions('forgive')" class=" h-20 w-24 mx-2 p-4 rounded-lg cursor-pointer @if($selected == 'forgive') bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-2 border-skin-base dark:border-skin-base-dark @else bg-gray-200 dark:bg-slate-800 @endif">
                    <i class="text-orange-400 fa-solid fa-heart-circle-exclamation fa-2x"></i>
                    <div class="text-xs dark:text-gray-300">Forgives</div>
                </div>
            </div>

            <!-- Query filters and sorting -->

            <div class="flex flex-col flex-1 items-stretch lg:p-2">
                <div class="flex flex-row gap-4 px-4 py-2">
                    <div>
                        <div class="uppercase text-sm font-bold mb-1 text-skin-inverted dark:text-skin-inverted-dark">
                            Per page
                        </div>
                        <select class="leading-3 bg-gray-300 dark:bg-slate-800 dark:text-gray-300" id="take" wire:model="take" wire:change="updateLimit()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="99999">All</option>
                        </select>
                        
                    </div>
    
                    <div>
                        <div class="uppercase text-sm font-bold mb-1 text-skin-inverted dark:text-skin-inverted-dark">
                            Sorting
                        </div>
                        <select class="leading-3 bg-gray-300 dark:bg-slate-800 dark:text-gray-300" id="order" wire:model="order" wire:change="updateOrder()">
                            <option value="desc">Latest</option>
                            <option value="asc">Oldest</option>
                        </select>
                        
                    </div>
                </div>
            
                <!-- Results table loop -->

                <div class="w-full mx-auto flex flex-col flex-1 overflow-x-auto">
                    <div class="w-full px-4">
                        <table class="w-full mx-auto">
                            <thead>
                                <tr>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Leaderboard</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Character name</td>
                                    <td class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark border border-white dark:border-gray-600">Added</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($interactions as $contact)
                                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-800 dark:odd:bg-slate-900 dark:text-gray-300">
                                        <td class="p-2 border border-gray-400">
                                            <a href="{{ route('player.curses', $contact->player_hash) }}">
                                                @if($contact->leaderboard_recieved && $contact->leaderboard_recieved->contact != null)
                                                    @if($contact->leaderboard_recieved->contact->type == 'friend')
                                                        <div class="font-bold text-green-500">
                                                            {{ $contact->leaderboard_recieved->contact->nickname}}
                                                        </div>
                                                    @elseif($contact->leaderboard_recieved->contact->type == 'dubious')
                                                        <div class="font-bold text-orange-500">
                                                            {{ $contact->leaderboard_recieved->contact->nickname}}
                                                        </div>
                                                    @else
                                                        <div class="font-bold text-red-500">
                                                            {{ $contact->leaderboard_recieved->contact->nickname}}
                                                        </div>
                                                    @endif
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
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-center bg-gray-300 dark:bg-slate-800 dark:text-gray-300">
                                            @if($selected == 'curse')
                                                No curses found. That's pretty darn impressive!
                                            @elseif($selected == 'trust')
                                                No trusts found. Time to go make some new friends!
                                            @elseif($selected == 'forgive')
                                                No forgives recieved. Either everyone loves you, or no one does.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Result count, page overview and navigation -->
                <div class="w-full flex flex-row mt-auto mx-2 p-4 self-end text-skin-muted dark:text-skin-muted-dark">
                    <div class="flex-1 text-left">
                        Total records: {{ $result_count }}
                    </div>

                    <div class="flex-1 text-center">
                        @if($result_count && ceil($result_count / $take) > 0)
                            Page: {{ ceil(($skip + $take) / $take) }} / {{ ceil($result_count / $take) }}
                        @endif
                    </div>

                    <div class="flex-1 text-right">
                        @if($result_count > $take)
                            @if($skip > 0)
                                <div wire:click="previousPage" class="inline-block p-2 rounded-lg text-white bg-skin-base dark:bg-skin-fill-dark">
                                    <button type="button">Previous</button>
                                </div>
                            @endif
                            @if($skip + $take <= $result_count)
                                <div wire:click="nextPage" class="inline-block p-2 rounded-lg text-white bg-skin-base dark:bg-skin-fill-dark">
                                    <button type="button">Next</button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
