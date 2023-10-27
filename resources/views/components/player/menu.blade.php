<div>
    <div class="text-center">
        <div class="flex flex-row justify-center">
            <a href="{{ route('player.curses', ['hash' => $hash]) }}" class="mx-2 p-2 rounded-xl @if(request()->routeIs('player.curses')) bg-blue-400 text-white @endif">Interactions</a>
            <a href="{{ route('player.lives', ['hash' => $hash]) }}" class="mx-2 p-2 rounded-xl @if(request()->routeIs('player.lives')) bg-blue-400 text-white @endif">Lives</a>
            <a href="{{ route('player.reports', ['hash' => $hash]) }}" class="mx-2 p-2 rounded-xl @if(request()->routeIs('player.reports')) bg-blue-400 text-white @endif">Reports</a>
        </div>
    </div>
</div>