<x-app-layout>

    @section("page-title")
        @if( $name )
            - Lives for {{ $name->leaderboard_name }}
        @else
            - Lives
        @endif
    @endsection

    <x-slot name="header">
        <livewire:player.profile-header :hash="$hash">
    </x-slot>
    <div class="w-full lg:grow lg:max-w-5xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">
        
        <div class="mt-6 bg-gray-200 p-2 lg:p-6 dark:bg-slate-700">

            <div class="text-4xl mt-2 text-center dark:text-gray-400">Number of lives lived</div>
            <div class="text-lg mt-2 mb-6 text-center dark:text-gray-900">Lives shorter than 3 years excluded</div>

            <div class="relative my-6 p-4 border border-blue-400 rounded-xl overflow-x-scroll dark:border-red-500">
                @if(count($lives) > 0)     
                    <table class="w-full mx-auto mt-4">
                        <thead class="p-2">
                            <tr class="p-2">
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">#</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Name</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Life ID</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Age</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Gender</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Location</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Died to</td>
                                <td class="p-2 bg-blue-400 dark:bg-red-500 text-white border border-gray-600">Died</td>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @forelse ($lives as $life)
                                <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-600 dark:odd:bg-slate-500 dark:text-gray-300">
                                    <td class="p-2 border border-gray-400">{{ (count($lives) - $loop->index) }}</td>
                                    <td class="p-2 border border-gray-400">
                                        <div onclick="Livewire.dispatch('openModal', {component: 'modals.character.details', arguments: {character_id: {{$life->character_id}}}})">{{ $life->name->name ?? '-UNNAMED-' }}</div>
                                    </td>
                                    <td class="p-2 border border-gray-400">{{ $life->character_id }}</td>
                                    <td class="p-2 border border-gray-400">{{ $life->age }}</td>
                                    <td class="p-2 border border-gray-400">{{ $life->gender }}</td>
                                    <td class="p-2 border border-gray-400">
                                        <a target="_blank" title="View on Wondible's OneMap" href="https://onemap.wondible.com/#x={{ $life->pos_x }}&y={{ $life->pos_y }}&z=29&s=17&t={{ time() }}">
                                            {{ $life->pos_x  }} / {{ $life->pos_y  }}
                                        </a>
                                    </td>
                                    <td class="p-2 border border-gray-400">{{ $life->died_to }}</td>
                                    <td class="p-2 border border-gray-400">{{ date('Y-m-d H:i:s ', $life->timestamp) }}</td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="px-2 text-center italic dark:text-gray-400">No lives found for this player...</div>
                @endif
            </div>
            @php
                $time_end = microtime(true);
        
                $end = $time_end - $time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
        </div>
    </div>
    
</x-app-layout>