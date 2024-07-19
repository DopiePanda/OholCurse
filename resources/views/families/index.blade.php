<x-app-layout>

    @section("page-title")
        - Family Overview
    @endsection

    <x-slot name="header">
        <h2 class="text-3xl text-gray-800 break-word leading-tight text-center dark:text-gray-200">
            Latest families with statistics
        </h2>

    </x-slot>
    <div class="grow py-1">

        <div class="w-1/3 mx-auto text-white p-4 bg-white dark:bg-slate-700 text-center">
            <div class="py-4">
                <div class="text-6xl">{{ $eve_count_week }}</div>
                <div class="mt-1 text-xl">Eves last 7 days</div>
                

                <div class="mt-6 bg-skin-fill dark:bg-skin-fill-dark p-3 rounded-lg">
                    <div class="text-sm">
                        Estimated world west movement last 24 hours:
                        <div class="text-lg">{{ $eve_count_day * 320 / 4 }}</div>
                    </div>
                    <div class="mt-1 text-sm">
                        Estimated world west movement last 7 days: 
                        <div class="text-lg">{{ $eve_count_week * 320 / 4 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-xl">
                @forelse ($eves as $eve)
                    @if($eve->name && $eve->name->name)
                        <div class="bg-white border border-blue-400 p-4 text-skin-base rounded-lg font-bold hover:bg-skin-fill hover:text-white dark:bg-slate-700 dark:text-skin-base-dark dark:border-0 dark:hover:bg-skin-fill-dark dark:hover:text-gray-200 [text-shadow:_0_1px_0_rgb(0_0_0_/_40%)]">
                            <a href="{{ route('families.view', ['character_id' => $eve->character_id]) }}">
                                <div class="mt-2 text-3xl">
                                    {{ explode(' ', $eve->name->name)[1] ?? '' }}
                                </div>
                            </a>
                            
                            <div class="inline-block mt-2 text-sm capitalize italic text-gray-600 dark:text-gray-400">
                                <a href="{{ route('player.interactions', ['hash' => $eve->leaderboard->player_hash]) }}">
                                    Founder: 
                                    <span class="text-gray-400 dark:text-white" >
                                        {{ $eve->leaderboard->leaderboard_name ?? 'MISSING' }}
                                    </span>
                                </a>
                            </div>

                            <div class="mt-1 text-sm capitalize italic text-gray-600 dark:text-gray-400">
                                Founded: {{ date('Y-m-d H:i', $eve->timestamp) ?? '' }}
                            </div>

                            <div class="mt-1 text-sm capitalize italic text-gray-600 dark:text-gray-400">
                                Biome: {{ $eve->family_type ?? '' }}
                            </div>
                        </div>
                    @endif  
                @empty
                    
                @endforelse
            </div>


        </div>
    </div>
</x-app-layout>