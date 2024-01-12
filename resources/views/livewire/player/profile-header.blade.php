<div class="bg-white dark:bg-slate-700">
    <div class="flex flex-row items-center justify-center">
        <div>

        </div>
        <div>   
            <h2 class="text-xl text-gray-800 dark:text-gray-200 leading-tight break-words text-center">
                <div>
                    @if(!empty($profile->leaderboard_id) && !empty($profile->leaderboard_name))
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
                        (Missing Player Name)
                    @endif
                </div>
            </h2>
        </div>
        <div class="lg:pl-8">
            <button title="Find previous relationships to this player" class="mx-2" wire:click="$dispatch('openModal', {component: 'modals.relation-search', arguments: {hash: '{{$profile->player_hash}}'}})">
                <i class="text-skin-base dark:text-skin-base-dark fa-solid fa-dna fa-2x"></i>
            </button>
        </div>
    </div>
</div>
