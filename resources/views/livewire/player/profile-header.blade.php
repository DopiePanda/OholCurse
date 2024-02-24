<div class="bg-white dark:bg-slate-700">
    <div class="flex flex-row items-center justify-center pr-8">
        <div class="flex grow text-center justify-center items-center">
            <div class="inline-block">
                <h2 class="inline-block mx-auto text-xl text-gray-800 dark:text-gray-200 leading-tight break-words text-center">
                    @if(isset($profile->leaderboard_id) && !empty($profile->leaderboard_id) && !empty($profile->leaderboard_name))
                        @if($contact)
                            <div class="flex lg:flex-row justify-center items-center font-semibold">
                                <span class="text-4xl">{{ $contact->nickname }}</span>
                                @auth
                                    <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$profile->player_hash}}'}})">
                                        @if($contact->type == 'friend')
                                            <i class="text-rose-500 fas fa-heart"></i>
                                        @elseif ($contact->type == 'dubious')
                                            <i class="text-orange-500 fas fa-question"></i>
                                        @else
                                            <i class="text-yellow-950 fas fa-poop"></i>
                                        @endif
                                    </button>
                                @endauth
                            </div>
                            <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $profile->leaderboard_id}}"
                                target="_blank">
                                <div title="{{ $profile->player_hash }}">{{ $profile->leaderboard_name }}</div>
                            </a>
                        @else
                            <div class="flex flex-row justify-center items-center font-semibold">
                                <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $profile->leaderboard_id}}"
                                    target="_blank">
                                    <span class="font-semibold text-4xl">{{ $profile->leaderboard_name }}</span>
                                </a>
                                @auth
                                    <button class="mx-2" wire:click="$dispatch('openModal', {component: 'contacts.manage', arguments: {hash: '{{$profile->player_hash}}'}})">
                                        <i class="text-gray-400 fas fa-star"></i>
                                    </button>
                                @endauth
                            </div>
                        @endif
                    @else
                        (Missing Leaderboard Name)
                    @endif
                </h2>
            </div>
            <div class="pt-1 ml-4 self-center inline-block">
                <div class="group relative ml-2 inline-block" wire:click="$dispatch('openModal', {component: 'modals.relation-search', arguments: {hash: '{{ $hash }}'}})">
                    
                    <svg class="w-10 h-10 text-skin-base dark:text-skin-base-dark hover:brightness-110" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v4m0 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V8m0 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                        </svg>
                    <span class="hidden md:block lg:block w-8 lg:w-96 py-0 lg:py-2 mt-8 z-50 group-hover:opacity-100 transition-opacity bg-gray-800 px-1 text-sm text-gray-100 rounded-md absolute left-1/2 
                    -translate-x-1/2 translate-y-full opacity-0 m-1 lg:m-4 mx-auto lg:-bottom-4">
                        Find previous relationships to this player
                    </span>
                </div>
                <div class="group relative ml-2 inline-block">
                    
                      <a href="{{ route('player.statistics', ['hash' => $hash]) }}">
                        <svg class="cursor-pointer w-10 h-10 text-skin-base dark:text-skin-base-dark hover:brightness-110" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4c0 .6-.4 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16c0 .6-.4 1-1 1H6a1 1 0 0 1-1-1V8c0-.4.1-.6.3-.8l4-4 .6-.2H18c.6 0 1 .4 1 1Z"/>
                          </svg>
                      </a>
                      <span class="hidden md:block lg:block w-8 lg:w-96 py-0 lg:py-2 mt-8 z-50 group-hover:opacity-100 transition-opacity bg-gray-800 px-1 text-sm text-gray-100 rounded-md absolute left-1/2 
                    -translate-x-1/2 translate-y-full opacity-0 m-1 lg:m-4 mx-auto lg:-bottom-4">
                        View player statistics
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
