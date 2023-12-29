<div class="bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark -mt-6 flex flex-col p-2">
    <div class="text-center">
        <div class="p-2 grid grid-rows-1 lg:grid-cols-4 lg:divide-x divide-gray-700 text-center">
            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.curses', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-skin-base dark:border-skin-base-dark @if(request()->routeIs('player.curses')) bg-skin-fill dark:bg-skin-fill-dark text-white border-0 @else text-skin-base dark:text-skin-base-dark @endif">
                    Interactions ({{ $counts['curses'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.lives', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-skin-base dark:border-skin-base-dark @if(request()->routeIs('player.lives')) bg-skin-fill dark:bg-skin-fill-dark text-white border-0 @else text-skin-base dark:text-skin-base-dark @endif">
                    Lives ({{ $counts['lives'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.reports', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-skin-base dark:border-skin-base-dark @if(request()->routeIs('player.reports')) bg-skin-fill dark:bg-skin-fill-dark text-white border-0 @else text-skin-base dark:text-skin-base-dark @endif">
                    Reports ({{ $counts['reports'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.records', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-skin-base dark:border-skin-base-dark @if(request()->routeIs('player.records')) bg-skin-fill dark:bg-skin-fill-dark text-white border-0 @else text-skin-base dark:text-skin-base-dark @endif">
                    Records ({{ $counts['recordsCount'] }})
                </a>
            </div>
        </div>
    </div>
</div>