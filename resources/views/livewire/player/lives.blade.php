<div class="w-full md:w-3/4 lg:max-w-6xl">

    <x-effects.backgrounds.animated-background :donator="$donator" wire:ignore />

    @section("page-title")
        @if( $profile )- Lives for {{ $profile->leaderboard_name }}@else- Lives @endif
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

            <livewire:player.component.lives :hash="$hash">

            @php
            $time_end = microtime(true);
    
            $end = $time_end - $start_time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
            
        </div>
    </div>
</div>