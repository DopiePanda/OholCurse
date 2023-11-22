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
            @foreach ($members as $member)
                <x-family-member :member="$member" />
            @endforeach
        </div>
    </div>
</x-app-layout>