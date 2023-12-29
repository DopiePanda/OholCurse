<x-app-layout>

    @section("page-title")- Weekly leaderboards @endsection

    <x-slot name="header">
        <h2 class="text-3xl text-gray-800 break-word leading-tight text-center dark:text-gray-200">
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
                        <div class="text-skin-base dark:text-skin-base-dark bg-skin-fill-muted border p-4 rounded-lg font-bold dark:bg-skin-fill-muted-dark border-skin-base dark:border-skin-base-dark">
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