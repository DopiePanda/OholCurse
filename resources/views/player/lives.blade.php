<x-app-layout>

    <x-effects.backgrounds.animated-background :donator="$donator" />

    @section("page-title")
        @if( $name )- Lives for {{ $name->leaderboard_name }}@else- Lives @endif
    @endsection

    <x-slot name="header">
        <livewire:player.profile-header :hash="$hash">
    </x-slot>
    <div class="w-full lg:grow lg:max-w-6xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">
        
        <div class="bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark mt-6 p-2 lg:p-6">

            <div class="text-4xl text-center text-skin-base dark:text-skin-base-dark">Number of lives lived</div>

            <div class="mt-6 grid grid-cols-2 gap-4">      
                <div class="p-4 text-center bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-green-600 rounded-xl">
                    <div class="font-bold text-6xl text-green-600">{{ $lives_normal }}</div>
                    <div class="mt-2 text-xl text-gray-800 dark:text-gray-400">Normal lives</div>
                </div>
                <div class="p-4 text-center bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-red-600 rounded-xl">
                    <div class="font-bold text-6xl text-red-600">{{ $lives_dt }}</div>
                    <div class="mt-2 text-xl text-gray-800 dark:text-gray-400">DT lives</div>
                </div>
            </div>

            <div class="mt-8 text-4xl text-center text-skin-base dark:text-skin-base-dark">Detailed life overview</div>
            <div class="text-lg mt-2 mb-6 text-center text-skin-muted dark:text-skin-muted-dark">Lives shorter than 3 years excluded</div>

            <div class="relative my-6 p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-scroll ">
                @if(count($lives) > 0)     
                    <table class="w-full mx-auto mt-4">
                        <thead class="p-2">
                            <tr class="p-2">
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">#</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Name</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Life ID</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Age</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Gender</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Location</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Died to</td>
                                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Died</td>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @forelse ($lives as $life)
                                <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                                    <td class="p-2 border border-gray-400">{{ (count($lives) - $loop->index) }}</td>
                                    <td class="p-2 border border-gray-400">
                                        <div class="cursor-pointer hover:text-skin-base dark:hover:text-skin-base-dark" onclick="Livewire.dispatch('openModal', {component: 'modals.character.details', arguments: {character_id: {{$life->character_id}}}})">{{ $life->name->name ?? '-UNNAMED-' }}</div>
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