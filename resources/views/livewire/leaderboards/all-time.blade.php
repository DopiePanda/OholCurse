<div>
    @section("page-title")- All-time leaderboards @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 text-center dark:text-gray-200">
            <div>Undefeated All-time <span id="ohol">OHOL</span> Champions</div>
        </h2>

    </x-slot>
    <div class="w-full lg:max-w-7xl py-1">
        <div class="text-center text-gray-800 dark:text-gray-400">
            <div class="cursor-pointer" wire:click="toggleGhostRecords()">
                @if($filter_ghosts)
                    <i class="fa-solid fa-user-check fa-2x"></i>
                    <div class="mt-1">View non-ghost leaderboards</div>
                @else
                    <i class="fa-solid fa-ghost fa-2x"></i>
                    <div class="mt-1">View ghost leaderboards</div>
                @endif
            </div>
        </div>
        <div id="g2h55" class="hidden bg-white border border-blue-400 rounded-xl p-4 text-center">
            <img class="mx-auto h-64" src="{{ asset('assets/extra/din.jpg') }}" />
            <div class="mt-4 text-xl italic">"I guess life is one hour if you life about it"</div>
        </div>
        <div class="flex flex-row gap-2 mt-6 sm:px-6 lg:px-8">
            <div>
                <div class="text-sm font-bold uppercase text-gray-300">
                    Order by
                </div>
                <div>
                    <select wire:model="order_by_col" wire:change="setOrderByColumn()" class="h-8 py-1 text-gray-300 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                        <option value="game_leaderboard_id">Leaderboard added</option>
                        <option value="timestamp">Date achieved</option>
                        <option value="amount">Record amount</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="text-sm font-bold uppercase text-gray-300">
                    Direction
                </div>
                <div>
                    <select wire:model="order_by_dir" wire:change="setOrderByDirection()" class="h-8 py-1 text-gray-300 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="relative overflow-x-auto mx-auto sm:px-6 lg:px-8">
            <table wire:loading.remove class="mx-auto mt-1 text-center border border-gray-400 shadow-lg">
                <thead>
                    <tr class="border-b border-gray-400">
                        <td class="p-4 text-white border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Category</td>
                        <td class="p-4 text-white border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Score</td>
                        <td class="p-4 text-white border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Leaderboard name</td>
                        <td class="p-4 text-white bg-skin-fill dark:bg-skin-fill-dark">Date</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @if($result->leaderboard->enabled == 1)
                            <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                                <td wire:click="$dispatch('openModal', { component: 'leaderboards.chart-modal', arguments: { leaderboard_id: {{ $result->game_leaderboard_id }}, ghost: {{ $filter_ghosts ? 1 : 0 }} }})" class=" p-4 border border-gray-400">
                                    <img class="mx-auto h-10" src="{{ asset($result->leaderboard->image) }}" />
                                    <div class="mt-1 text-sm font-semibold">{{ $result->leaderboard->label }}</div>
                                </td>
                                <td wire:click="$dispatch('openModal', { component: 'leaderboards.chart-modal', arguments: { leaderboard_id: {{ $result->game_leaderboard_id }}, ghost: {{ $filter_ghosts ? 1 : 0 }} }})" class="p-4 text-xl font-bold border border-gray-400">
                                    {{ $filter_ghosts ? $result->currentGhostRecord->amount : $result->currentRecord->amount ?? 0 }}
                                    
                                </td>
                                <td class="p-4 border border-gray-400">
                                    @if(isset($result->currentRecord->character->player_hash) && isset($result->currentRecord->player->leaderboard_name))
                                        @if($filter_ghosts)
                                            <a class="font-semibold text-skin-base dark:text-skin-base-dark" href="{{ route('player.interactions', ['hash' => $result->currentGhostRecord->character->player_hash]) }}">
                                                {{ $result->currentGhostRecord->player->contact->nickname ?? $result->currentGhostRecord->player->leaderboard_name }}
                                            </a>
                                        @else
                                            <a class="font-semibold text-skin-base dark:text-skin-base-dark" href="{{ route('player.interactions', ['hash' => $result->currentRecord->character->player_hash]) }}">
                                                {{ $result->currentRecord->player->contact->nickname ?? $result->currentRecord->player->leaderboard_name }}
                                            </a>
                                        @endif
                                    @else
                                        <span title="Check again after 9AM tomorrow for updated data">
                                            - LEADERBOARD MISSING -
                                        </span>
                                    @endif

                                    <div class="text-xs text-gray-600 italic dark:text-gray-800">
                                        - playing as -
                                    </div>
                                    <div class="mt-1 text-sm text-black lowercase capitalize dark:text-gray-200">
                                        @if(isset($result->currentRecord->lifeName->name)) 
                                            {{ $filter_ghosts ? $result->currentGhostRecord->lifeName->name : $result->currentRecord->lifeName->name }} 
                                        @else
                                            <span title="Check again after 9AM tomorrow for updated data">
                                                (- NAME MISSING -)
                                            </span>
                                        @endif 
                                    </div>
                                </td>
                                <td class="p-4 border border-gray-400">
                                    @if($filter_ghosts)
                                        {{ date('Y-m-d H:i', $result->currentGhostRecord->timestamp) ?? "(- DATE MISSING -)" }}
                                    @else
                                        {{ date('Y-m-d H:i', $result->currentRecord->timestamp) ?? "(- DATE MISSING -)" }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @empty

                    @endforelse
                </tbody>
            </table>
            <!-- If your name is on the page, you owe a thank you to Hopie 8-) -->
        </div>
    </div>

    @section("before-body-end")
        <script src="{{ asset('assets/js/extra.js') }}"></script> 
    @endsection
</div>
