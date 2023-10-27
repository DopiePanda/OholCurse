<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight break-all">
            Leaderboards
        </h2>

    </x-slot>
    <div class="py-1">

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-2xl uppercase font-bold text-center">Biggest corn shucker</div>
            <div class="text-sm uppercase font-bold text-center">Period: 2023-09-20 to 2023-09-21 (20:00 UTC)</div>
            <div class="mt-2 text-lg text-center">Most amount of corns shucked in a life</div>
            <table class="mt-3 table table-auto w-full text-center border border-gray-600">
                <thead>
                    <tr class="border-b border-gray-600">
                        <td class="p-2 border-r border-gray-600">Place</td>
                        <td class="p-2 border-r border-gray-600">Score</td>
                        <td class="p-2 border-r border-gray-600">Leaderboard</td>
                        <td class="p-2">Character</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($corns as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500">
                        @else
                            <tr class="bg-gray-200">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">{{ $result->life->leaderboard->leaderboard_name }}</td>
                            <td class="p-2">{{ $result->name->name }} ({{ $result->character_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-2xl uppercase font-bold text-center">Most prolific stonemason</div>
            <div class="text-sm uppercase font-bold text-center">Period: 2023-09-20 to 2023-09-21 (20:00 UTC)</div>
            <div class="mt-2 text-lg text-center">Most amount of walls cut in a life</div>
            <table class="mt-3 table table-auto w-full text-center border border-gray-600">
                <thead>
                    <tr class="border-b border-gray-600">
                        <td class="p-2 border-r border-gray-600">Place</td>
                        <td class="p-2 border-r border-gray-600">Score</td>
                        <td class="p-2 border-r border-gray-600">Leaderboard</td>
                        <td class="p-2">Character</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($walls as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500">
                        @else
                            <tr class="bg-gray-200">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">{{ $result->life->leaderboard->leaderboard_name }}</td>
                            <td class="p-2">{{ $result->name->name }} ({{ $result->character_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-2xl uppercase font-bold text-center">God of poop shoveling</div>
            <div class="text-sm uppercase font-bold text-center">Period: 2023-09-20 to 2023-09-21 (20:00 UTC)</div>
            <div class="mt-2 text-lg text-center">Mots poops shoveled in one life</div>
            <table class="mt-3 table table-auto w-full text-center border border-gray-600">
                <thead>
                    <tr class="border-b border-gray-600">
                        <td class="p-2 border-r border-gray-600">Place</td>
                        <td class="p-2 border-r border-gray-600">Score</td>
                        <td class="p-2 border-r border-gray-600">Leaderboard</td>
                        <td class="p-2">Character</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dungs as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500">
                        @else
                            <tr class="bg-gray-200">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">{{ $result->life->leaderboard->leaderboard_name }}</td>
                            <td class="p-2">{{ $result->name->name }} ({{ $result->character_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-2xl uppercase font-bold text-center">Expert compost maker</div>
            <div class="text-sm uppercase font-bold text-center">Period: 2023-09-20 to 2023-09-21 (20:00 UTC)</div>
            <div class="mt-2 text-lg text-center">Mots compost made in one life</div>
            <table class="mt-3 table table-auto w-full text-center border border-gray-600">
                <thead>
                    <tr class="border-b border-gray-600">
                        <td class="p-2 border-r border-gray-600">Place</td>
                        <td class="p-2 border-r border-gray-600">Score</td>
                        <td class="p-2 border-r border-gray-600">Leaderboard</td>
                        <td class="p-2">Character</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($composts as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500">
                        @else
                            <tr class="bg-gray-200">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">{{ $result->life->leaderboard->leaderboard_name }}</td>
                            <td class="p-2">{{ $result->name->name }} ({{ $result->character_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-2xl uppercase font-bold text-center">MLG Mouflon hunter</div>
            <div class="text-sm uppercase font-bold text-center">Period: 2023-09-20 to 2023-09-21 (20:00 UTC)</div>
            <div class="mt-2 text-lg text-center">Mots mouflons brought home in one life</div>
            <table class="mt-3 table table-auto w-full text-center border border-gray-600">
                <thead>
                    <tr class="border-b border-gray-600">
                        <td class="p-2 border-r border-gray-600">Place</td>
                        <td class="p-2 border-r border-gray-600">Score</td>
                        <td class="p-2 border-r border-gray-600">Leaderboard</td>
                        <td class="p-2">Character</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mouflons as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500">
                        @else
                            <tr class="bg-gray-200">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">{{ $result->life->leaderboard->leaderboard_name }}</td>
                            <td class="p-2">{{ $result->name->name }} ({{ $result->character_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>