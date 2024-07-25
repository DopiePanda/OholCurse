<div class="w-full">
    <div class="text-4xl text-center text-skin-base dark:text-skin-base-dark">
        Find griefers twinning with a specified player hash
    </div>
    <div class="mx-auto w-1/3 px-8 py-4 mt-6 bg-gray-200">
        <div class="mb-2">
            <div>Enter player hash</div>
            <div class="mt-1">
                <input class="w-full" wire:model="hash" type="text" />
            </div>
        </div>
        <div class="mb-2">
            <div>Exclude SID lives (> 1 year)</div>
            <div class="mt-1">
                <input wire:model="filter_sid" type="checkbox" />
            </div>
        </div>
        <div class="mb-2">
            <div>Exclude DT lives</div>
            <div class="mt-1">
                <input wire:model="filter_dt" type="checkbox" />
            </div>
        </div>
        <div class="mt-2">
            <button class="py-2 text-white block bg-green-500 w-full h-full" type="button" wire:click="search()">Search</button>
        </div>
    </div>

    <div wire:loading class="w-full mx-auto text-center mt-12 text-primary-500">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <div class="text-skin-muted dark:text-skin-muted-dark">Searching for griefer twin lives</div>
    </div>

    @if($siblings && count($siblings) > 0)
    <div class="mt-4 mb-2 text-gray-400 text-4xl text-center">
        Results for {{ $leaderboard->leaderboard_name }}
    </div>
    <div class="mx-auto w-2/3 mt-6 p-4">
        <table class="w-full table-fixed text-left">
            <thead>
                <tr class="text-left">
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">#</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Date</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Nickname</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Leaderboard</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Character</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Birth X/Y</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Death X/Y</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Age</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Character ID</td>
                    <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Parent ID</td>
                </tr>
            </thead>
            <tbody>
            @forelse ($siblings as $child)
                <tr class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark dark:text-gray-300">
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ $loop->index }}</td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ date('Y-m-d H:i', $child->timestamp) }}</td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ $child->griefer->group->name ?? $child->player_hash }}</td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">
                        <a href="{{ route('player.interactions', ['hash' => $child->player_hash]) }}" target="_blank">
                            {{ $child->leaderboard->leaderboard_name ?? $child->player_hash }}
                        </a>
                    </td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">
                        {{ $child->name->name ?? 'UNNAMED' }}
                    </td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">
                        <a target="_blank" title="View on Wondible's OneMap" href="https://onemap.wondible.com/#x={{ $child->pos_x }}&y={{ $child->pos_y }}&z=25&s=17&t={{ $child->timestamp }}">
                            {{ $child->pos_x }}, {{ $child->pos_y }}
                        </a>
                    </td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">
                        <a target="_blank" title="View on Wondible's OneMap" href="https://onemap.wondible.com/#x={{ $child->death->pos_x }}&y={{ $child->death->pos_y }}&z=25&s=17&t={{ $child->death->timestamp }}">
                            {{ $child->death->pos_x }}, {{ $child->death->pos_y }}
                        </a>
                    </td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ $child->death->age ?? 'N/A' }}</td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ $child->character_id }}</td>
                    <td class="p-2 border-b border-gray-400 dark:border-gray-800">{{ $child->parent_id }}</td>
                </tr>
            @empty

            @endforelse

            </tbody>
        </table>
    </div>
    @endif
</div>
