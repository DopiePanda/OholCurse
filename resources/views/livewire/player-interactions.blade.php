<div>
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

            <livewire:player.component.interaction-sent :interactions="$curses_sent" title="Curses sent" handle="1">
            <livewire:player.component.interaction-recieved :interactions="$curses_recieved" title="Curses recieved" handle="2">
            <livewire:player.component.interaction-sent :interactions="$forgives_sent" title="Forgives sent" handle="3">
            <livewire:player.component.interaction-recieved :interactions="$forgives_recieved" title="Forgives recieved" handle="4">
            <livewire:player.component.interaction-sent :interactions="$trusts_sent" title="Trusts sent" handle="5">
            <livewire:player.component.interaction-recieved :interactions="$trusts_recieved" title="Trusts recieved" handle="6">

            @php
            $time_end = microtime(true);
    
            $end = $time_end - $start_time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>

        </div>
</div>
