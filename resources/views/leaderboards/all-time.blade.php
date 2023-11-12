<x-app-layout>

    @section("page-title")
        - All-time leaderboards
    @endsection

    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 text-center dark:text-gray-200">
            <div>Undefeated All-time <span id="ohol">OHOL</span> Champions</div>
        </h2>

    </x-slot>
    <div class="max-w-7xl py-1 overflow-x-scroll">
        <div id="g2h55" class="hidden bg-white border border-blue-400 rounded-xl p-4 text-center">
            <img class="mx-auto h-64" src="{{ asset('assets/extra/din.jpg') }}" />
            <div class="mt-4 text-xl italic">"I guess life is one hour if you life about it"</div>
        </div>
        <div class="overflow-x-scroll py-6 max-w-screen mx-auto sm:px-6 lg:px-8">
            <table wire:loading.remove class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
                <thead>
                    <tr class="border-b border-gray-400">
                        <td class="p-4 bg-blue-400 text-white border-r border-gray-400 dark:bg-red-500">Category</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-gray-400 dark:bg-red-500">Score</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-gray-400 dark:bg-red-500">Leaderboard name</td>
                        <td class="p-4 bg-blue-400 text-white dark:bg-red-500">Date</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @if($result->leaderboard->enabled == 1)
                        <tr class="bg-white even:bg-gray-300 odd:bg-white dark:even:bg-slate-600 dark:odd:bg-slate-500 dark:text-gray-300">
                                <td class=" p-4 border border-gray-400">
                                    <img class="mx-auto h-10" src="{{ asset($result->leaderboard->image) }}" />
                                    <div class="mt-1 text-sm font-semibold">{{ $result->leaderboard->label }}</div>
                                </td>
                                <td class="p-4 text-xl font-bold border border-gray-400">
                                    {{ $result->amount ?? 0 }}
                                </td>
                                <td class="p-4 border border-gray-400">
                                    @if(isset($result->character->player_hash) && isset($result->playerName->leaderboard_name)) 
                                        <a class="text-blue-400 font-semibold dark:text-red-400" href="{{ route('player.curses', ['hash' => $result->character->player_hash]) }}">
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

    @section("before-body-end")
        <script src="{{ asset('assets/js/extra.js') }}"></script> 
    @endsection

</x-app-layout>
