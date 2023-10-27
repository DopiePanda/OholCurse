<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight break-all text-center">
            <div class="font-semibold text-4xl">
                @if(!empty($name->leaderboard_id) && !empty($name->leaderboard_id))
                <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $name->leaderboard_id}}"
                    target="_blank">
                    {{ $name->leaderboard_name }}
                </a>
                @else
                    (Missing Player Name)
                @endif
            </div>
            <div class="mt-2 italic">
                {{ $hash }}
            </div>
        </h2>

    </x-slot>
    <div class="py-1 overflow-x-scroll">

        <x-player.menu :hash="$hash" />
        
        <table class="mt-4 border border-gray-600 overflow-x-scroll">
            <thead class="p-2">
                <tr class="p-2 border border-gray-600">
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Name</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Life ID</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Age</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Gender</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Location</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Died to</td>
                    <td class="p-2 bg-blue-400 text-white border border-gray-600">Died</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($lives as $life)
                    <tr>
                        <td class="p-2 border border-gray-200">{{ $life->name->name ?? '-UNNAMED-' }}</td>
                        <td class="p-2 border border-gray-200">{{ $life->character_id }}</td>
                        <td class="p-2 border border-gray-200">{{ $life->age }}</td>
                        <td class="p-2 border border-gray-200">{{ $life->gender }}</td>
                        <td class="p-2 border border-gray-200">{{ $life->location }}</td>
                        <td class="p-2 border border-gray-200">{{ $life->died_to }}</td>
                        <td class="p-2 border border-gray-200">{{ date('Y-m-d H:i:s ', $life->timestamp) }}</td>
                    </tr>
                @empty
                    
                @endforelse
            </tbody>
        </table>
        @php
            $time_end = microtime(true);
    
            $end = $time_end - $time;
        @endphp
        <div class="text-center text-sm mt-2 font-gray-400">Page load time: {{ round($end, 3) }}s</div>
    </div>
</x-app-layout>