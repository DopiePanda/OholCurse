<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl text-gray-800 break-word leading-tight text-center">
            Weekly top list of pro-gamers
        </h2>

    </x-slot>
    <div class="py-1">

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center text-xl">
                @forelse ($lists as $list)
                    @if($list->multi)
                        <a href="{{ route('leaderboards.weekly.multi', ['id' => $list->id]) }}">
                    @else
                        <a href="{{ route('leaderboards.weekly.single', ['object_id' => $list->object_id]) }}">
                    @endif
                        <div class="bg-white border border-yellow-500 p-4 text-yellow-800 rounded-lg font-bold">
                            <div><img class="mx-auto h-12 max-h-12" src="{{ asset($list->image) }}" /></div>
                            <div class="mt-2">{{ $list->label }}</div>
                        </div>
                    </a>    
                @empty
                    
                @endforelse
            </div>


        </div>
    </div>
</x-app-layout>