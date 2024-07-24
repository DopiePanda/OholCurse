<div class="z-30 bg-white dark:bg-slate-700">
    <div class="flex flex-col items-center justify-center pr-8">
        <div class="flex grow text-center justify-center items-center">
            <div class="inline-block">
                <h2 class="inline-block mx-auto text-xl text-gray-800 dark:text-gray-200 leading-tight break-words">
                    @if(isset($profile->leaderboard_id) && !empty($profile->leaderboard_id) && !empty($profile->leaderboard_name))
                        @if($contact)
                            <div class="flex lg:flex-row justify-center items-center font-semibold">
                                @can('view all user contacts')
                                    <div>
                                        <div>
                                            <span class="block mx-2">
                                                <i wire:click="$dispatch('openModal', { component: 'contacts.admin-modal', arguments: {hash: '{{$profile->player_hash}}', leaderboard: '{{$profile->leaderboard_name}}'}})"class="fa-solid fa-address-card text-gray-400"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="mx-2">
                                                <i wire:click="$dispatch('openModal', { component: 'admin.player.messages', arguments: {hash: '{{$profile->player_hash}}'}})"class="fa-solid fa-message text-gray-400"></i>
                                            </span>
                                        </div>
                                    </div>
                                @endcan
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
                            <a class="inline-block text-left" href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $profile->leaderboard_id}}"
                                target="_blank">
                                <div title="{{ $profile->player_hash }}">{{ $profile->leaderboard_name }}</div>
                            </a>
                        @else
                            <div class="flex flex-row justify-center items-center font-semibold">
                                @can('view all user contacts')
                                    <div>
                                        <div>
                                            <span class="block mx-2">
                                                <i wire:click="$dispatch('openModal', { component: 'contacts.admin-modal', arguments: {hash: '{{$profile->player_hash}}', leaderboard: '{{$profile->leaderboard_name}}'}})"class="fa-solid fa-address-card text-gray-400"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="mx-2">
                                                <i wire:click="$dispatch('openModal', { component: 'admin.player.messages', arguments: {hash: '{{$profile->player_hash}}'}})"class="fa-solid fa-message text-gray-400"></i>
                                            </span>
                                        </div>
                                    </div>
                                @endcan
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
                @if($profile->leaderboard_id)
                    <div class="text-gray-400 text-xs font-bold">ID: {{ $profile->leaderboard_id }} </div>
                @endif
                
                @if($donator != null && $donator->show_badges == "hide")

                @else
                    <div class="z-60 mt-2 flex flex-row justify-start space-x-2">
                        @if(count($badges) > 0)
                            @foreach ($badges as $badge)
                                <div wire:click="$dispatch('openModal', {component: 'modals.profile-badge', arguments: {hash: '{{ $hash }}', badge_id: {{$badge->id}}}})">
                                    <img class="h-8 hue-rotate-skin saturate-skin contrast-skin brightness-skin" src="{{ asset($badge->badge->image_small) }}" alt="{{ $badge->badge->name }}" title="{{ $badge->badge->name }}" />
                                </div>
                            @endforeach
                        @endif
                    </div>
               @endif

            </div>
            <div class="pt-1 ml-4 self-center inline-block">
                <div class="group relative ml-2 inline-block" wire:click="$dispatch('openModal', {component: 'modals.relation-search', arguments: {hash: '{{ $hash }}'}})">
                    
                    <svg class="w-10 h-10 text-skin-base dark:text-skin-base-dark hover:brightness-110" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v4m0 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V8m0 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                    </svg>
                </div>
                <div class="group relative ml-2 inline-block">
                    
                      <a href="{{ route('player.statistics', ['hash' => $hash]) }}">
                        <svg class="cursor-pointer w-10 h-10 text-skin-base dark:text-skin-base-dark hover:brightness-110" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4c0 .6-.4 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16c0 .6-.4 1-1 1H6a1 1 0 0 1-1-1V8c0-.4.1-.6.3-.8l4-4 .6-.2H18c.6 0 1 .4 1 1Z"/>
                          </svg>
                      </a>
                </div>
            </div>
        </div>
    </div>
</div>
