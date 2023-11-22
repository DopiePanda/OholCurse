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

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-xl">
                @forelse ($eves as $eve)
                    @if($eve->name && $eve->name->name)
                    <a href="{{ route('families.view', ['character_id' => $eve->character_id]) }}">
                        <div class="bg-white border border-blue-400 p-4 text-blue-400 rounded-lg font-bold hover:bg-blue-400 hover:text-white dark:bg-slate-700 dark:text-red-500 dark:border-0 dark:hover:bg-red-500 dark:hover:text-gray-200">
                            <div class="mt-2 text-3xl">
                                {{ explode(' ', $eve->name->name)[1] ?? '' }}
                            </div>
                            
                            <div class="mt-2 text-sm capitalize italic text-gray-600 dark:text-gray-400">
                                Founded: {{ date('Y-m-d H:i', $eve->timestamp) ?? '' }}
                            </div>

                            <div class="mt-2 text-sm capitalize italic text-gray-600 dark:text-gray-400">
                                Biome: {{ $eve->family_type ?? '' }}
                            </div>
                        </div>
                    </a>
                    @endif  
                @empty
                    
                @endforelse
            </div>


        </div>
    </div>
</x-app-layout>