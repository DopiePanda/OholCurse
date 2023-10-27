<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 text-center">
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
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Category</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Score</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Leaderboard name</td>
                        <td class="p-4 bg-blue-400 text-white">Date</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="bg-white">
                            <td class=" p-4 border border-gray-400">
                                <img class="mx-auto h-10" src="{{ asset($result->image) }}" />
                                <div class="mt-1 text-sm font-semibold">{{ $result->label }}</div>
                            </td>
                            <td class="p-4 text-xl font-bold border border-gray-400">
                                {{ $result->record->amount ?? 0 }}
                            </td>
                            <td class="p-4 border border-gray-400">
                                @if(isset($result->record->character->player_hash) && isset($result->record->playerName->leaderboard_name)) 
                                    <a class="text-blue-400 font-semibold" href="{{ route('player.curses', ['hash' => $result->record->character->player_hash]) }}">
                                        {{ $result->record->playerName->leaderboard_name }}
                                    </a>
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        - LEADERBOARD MISSING -
                                    </span>
                                @endif

                                <div class="text-xs text-gray-600 italic">
                                    - playing as -
                                </div>
                                <div class="mt-1 text-sm text-black lowercase capitalize">
                                    @if(isset($result->record->lifeName->name)) 
                                        {{ $result->record->lifeName->name }} 
                                    @else
                                        <span title="Check again after 9AM tomorrow for updated data">
                                            (- NAME MISSING -)
                                        </span>
                                    @endif 
                                </div>
                            </td>
                            <td class="p-4 border border-gray-400">
                                @if(isset($result->record->timestamp))
                                    {{ date('Y-m-d H:i', $result->record->timestamp) }}
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- DATE MISSING -)
                                    </span>
                                @endif
                            </td>
                        </tr>
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
