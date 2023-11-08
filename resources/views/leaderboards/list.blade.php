<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl fond-bold text-gray-800 leading-tight text-center">
            {{ $object->page_title ?? 'Top 10 Last 7 Days' }}
        </h2>
        @if($object)
            <img class="mx-auto h-22" src="{{ asset($object->image) }}" title="{{ $object->object->name }}" />
            @if($object->multi)
                <div class="text-center text-sm italic">
                    Object ID(s): 
                    @foreach(json_decode($object->multi_objects) as $single)
                        <span>@if(!$loop->last) {{ $single.',' }}  @else {{ $single }}  @endif</span>
                    @endforeach
                </div>
            @else
                <div class="text-center text-sm italic">Object ID(s): {{ $object->object_id }}</div>
            @endif
        @endif
    </x-slot>
    <div class="max-w-7xl py-1 overflow-x-scroll">
        <div class="my-2 py-2 text-center">
            <div class="font-bold text-xl">Period:</div>
            <div class="text-lg">{{ $start->format('Y-m-d H:i') }} -  {{ $end->format('Y-m-d H:i') }}</div>
        </div>
        <div class="overflow-x-scroll py-6 max-w-screen mx-auto sm:px-6 lg:px-8">
            <table wire:loading.remove class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
                <thead>
                    <tr class="border-b border-gray-400">
                        <td class="p-2 border-r border-gray-400">Place</td>
                        <td class="p-2 border-r border-gray-400">Score</td>
                        <td class="p-2 border-r border-gray-400">Leaderboard name</td>
                        <td class="p-2 border-r border-gray-400">Character name (Character ID)</td>
                        <td class="p-2">Date</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @if($loop->index == 0)    
                            <tr class="p-2 bg-green-500 border-b border-green-700">
                        @elseif($loop->index == 1)
                            <tr class="p-2 bg-lime-500 border-b border-lime-700">
                        @elseif($loop->index == 2)
                            <tr class="p-2 bg-yellow-500 border-b border-yellow-700">
                        @else
                            <tr class="p-2 bg-gray-200 border-b border-gray-400">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">
                                @if(isset($result->life->leaderboard->player_hash) && isset($result->life->leaderboard->leaderboard_name)) 
                                <a href="{{ route('player.curses', ['hash' => $result->life->leaderboard->player_hash]) }}">
                                    {{ $result->life->leaderboard->contact->nickname ?? $result->life->leaderboard->leaderboard_name }}
                                </a>
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        - LEADERBOARD MISSING -
                                    </span>
                                @endif
                            </td>
                            <td class="p-2">
                                @if(isset($result->name->name)) 
                                    {{ $result->name->name }} 
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- NAME MISSING -)
                                    </span>
                                @endif 
                                @if(isset($result->character_id))
                                    ({{ $result->character_id }})
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- LIFE ID MISSING -)
                                    </span>
                                @endif
                            </td>
                            <td class="p-2">
                                @if(isset($result->life->timestamp))
                                    {{ date('Y-m-d H:i', $result->life->timestamp) }}
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
        </div>
    </div>
</x-app-layout>