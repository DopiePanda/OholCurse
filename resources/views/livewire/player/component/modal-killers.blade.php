<div class="p-8 dark:bg-slate-800">
    <div class="text-4xl text-center text-skin-base dark:text-skin-base-dark">
        Who has killed {{ $player ? $player->leaderboard_name : 'this player' }}?
    </div>
    <div class="mt-1 text-sm text-center text-skin-muted dark:text-skin-muted-dark">
        The table below lists all players that have killed {{ $player ? $player->leaderboard_name : 'this player' }}
    </div>
    <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
    <div class="relative my-6 pb-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-auto">
        <table class="w-full table-auto text-left text-sm">
            <thead>
                <tr class="text-left">
                    <th class="px-2 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Date</th>
                    <th class="px-1 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Leaderboard</th>
                    <th class="px-1 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Character name</th>
                    <th class="px-1 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Family</th>
                    <th class="px-1 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Death age</th>
                    <th class="px-1 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">Died to</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($players as $killer)
                    <tr class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark dark:text-gray-300">
                        <td class="px-2 py-2 border-b border-gray-400 dark:border-gray-800">{{ date('Y-m-d H:i', $killer->timestamp) }}</td>
                        <td class="px-1 py-2 border-b border-gray-400 dark:border-gray-800">
                            <a href="{{ route('player.interactions', ['hash' => $killer->player_hash ?? 'error']) }}" tabindex="-1">
                                <span class="text-skin-base dark:text-skin-base-dark">
                                    {{ $killer->leaderboard->leaderboard_name ?? 'MISSING' }}
                                </span>
                                <div class="text-gray-700 text-sm inline-block">
                                    (<span class="text-green-600">{{ round($killer->leaderboard->score->gene_score, 0) ?? 0 }}</span> / 
                                    <span class="text-red-600">{{ $killer->leaderboard->score->curse_score ?? 0 }}</span>)
                                </div>
                            </a>
                        </td>
                        <td class="px-1 py-2 border-b border-gray-400 dark:border-gray-800">
                            <a href="{{ route('player.lives', ['hash' => $killer->player_hash ?? 'error']) }}" tabindex="-1">
                                @if($killer->gender == 'male')
                                    <i title="Male" class="text-blue-500 mr-1 fa-solid fa-mars"></i>
                                @else
                                    <i title="Female" class="text-pink-500 mr-1 fa-solid fa-venus"></i>
                                @endif
                                <span class="capitalize">{{ strtolower($killer->name->name) }}</span>
                                <span class="text-xs">
                                    ({{ $killer->character_id }})
                                </span>
                            </a>
                        </td>
                        <td class="px-1 py-2 border-b border-gray-400 dark:border-gray-800">{{ ucfirst($killer->family_type) }}</td>
                        <td class="px-1 py-2 border-b border-gray-400 dark:border-gray-800">{{ $killer->death->age }}</td>
                        <td class="px-1 py-2 border-b border-gray-400 dark:border-gray-800">{{ $killer->death->died_to }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
