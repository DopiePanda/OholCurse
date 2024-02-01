<div class="w-full md:w-3/4 lg:w-3/5">
    @section("page-title")
        @if( $profile && isset($profile->leaderboard_name) )- Interactions for {{ $profile->leaderboard_name }}@else- Interactions @endif
    @endsection

    <x-slot name="header">

        <livewire:player.profile-header :hash="$hash">

    </x-slot>
    <div class="grow w-full max-w-7xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">

        <div class="w-full mt-6 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark p-2 lg:p-6">
            <div class="p-4 mb-4 text-center">
                <div class="grid grid-cols-2 gap-4">      
                    <div class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-green-600 rounded-xl">
                        <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-good.png') }}" /></div>
                        <div class="text-xl dark:text-gray-400">Gene score</div>
                        @if(isset($profile->score->gene_score))
                            <div class="font-bold text-4xl text-green-600">{{ round($profile->score->gene_score, 2) }}</div>
                        @else
                            <div class="font-bold text-4xl text-green-600">0</div>
                        @endif
                    </div>
                    <div class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-red-600 rounded-xl">
                        <div><img class="mx-auto h-32" src="{{ asset('assets/leaderboard/score-evil.png') }}" /></div>
                        <div class="text-xl dark:text-gray-400">Curse score</div>
                        @if(isset($profile->score->curse_score))
                            <div class="font-bold text-4xl text-red-600">{{ $profile->score->curse_score }}</div>
                        @else
                            <div class="font-bold text-4xl text-green-600">0</div>
                        @endif
                    </div>
                </div>
            </div>

            <livewire:player.component.interaction-sent type="curse" :hash="$hash">
            <livewire:player.component.interaction-recieved type="curse" :hash="$hash">
            <livewire:player.component.interaction-sent type="forgive" :hash="$hash">
            <livewire:player.component.interaction-recieved type="forgive" :hash="$hash">
            <livewire:player.component.interaction-sent type="trust" :hash="$hash">
            <livewire:player.component.interaction-recieved type="trust" :hash="$hash">

            @php
            $time_end = microtime(true);
    
            $end = $time_end - $start_time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>

        </div>
</div>
