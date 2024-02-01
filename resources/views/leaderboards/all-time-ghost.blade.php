<x-app-layout>

    @section("page-title")- All-time Ghost Leaderboards @endsection

    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 text-center dark:text-gray-200">
            <div>Undefeated All-time <span id="ohol">OHOL</span> Ghosts</div>
        </h2>

    </x-slot>
    <div class="w-full lg:max-w-7xl py-1">
        <div class="text-center text-gray-800 dark:text-gray-400">
            <a href="{{ route('leaderboards.all-time') }}">
                <i class="fa-solid fa-user-check fa-2x"></i>
                <div class="mt-1">View non-ghost leaderboards</div>
            </a>
        </div>
        <div class="relative overflow-x-scroll py-6 mx-auto sm:px-6 lg:px-8">
            <table wire:loading.remove class="mx-auto mt-3 text-center border border-gray-400 shadow-lg">
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
                                <td class=" p-4 border border-gray-400">
                                    <img class="mx-auto h-10" src="{{ asset($result->leaderboard->image) }}" />
                                    <div class="mt-1 text-sm font-semibold">{{ $result->leaderboard->label }}</div>
                                </td>
                                <td class="p-4 text-xl font-bold border border-gray-400">
                                    {{ $result->amount ?? 0 }}
                                </td>
                                <td class="p-4 border border-gray-400">
                                    @if(isset($result->character->player_hash) && isset($result->playerName->leaderboard_name)) 
                                        <a class="font-semibold text-skin-base dark:text-skin-base-dark" href="{{ route('player.interactions', ['hash' => $result->character->player_hash]) }}">
                                            {{ $result->playerName->contact->nickname ?? $result->playerName->leaderboard_name }}
                                        </a>
                                    @else
                                        <span title="Check again after 9AM tomorrow for updated data">
                                            - LEADERBOARD MISSING -
                                        </span>
                                    @endif

                                    <div class="text-xs text-gray-600 italic dark:text-gray-800">
                                        - playing as -
                                    </div>
                                    <div class="mt-1 text-sm text-black lowercase capitalize dark:text-gray-200">
                                        @if(isset($result->lifeName->name)) 
                                            {{ $result->lifeName->name }} 
                                        @else
                                            <span title="Check again after 9AM tomorrow for updated data">
                                                (- NAME MISSING -)
                                            </span>
                                        @endif 
                                    </div>
                                </td>
                                <td class="p-4 border border-gray-400">
                                    @if(isset($result->timestamp))
                                        {{ date('Y-m-d H:i', $result->timestamp) }}
                                    @else
                                        <span title="Check again after 9AM tomorrow for updated data">
                                            (- DATE MISSING -)
                                        </span>
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
</x-app-layout>
