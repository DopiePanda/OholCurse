<div class="-mt-6 flex flex-col p-2 bg-blue-400 dark:bg-transparent">
    <div class="text-center">
        <div class="p-2 grid grid-rows-1 lg:grid-cols-4 lg:divide-x divide-gray-700 text-center">
            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.curses', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.curses')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Interactions ({{ $counts['curses'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.lives', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.lives')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Lives ({{ $counts['lives'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.reports', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.reports')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Reports ({{ $counts['reports'] }})
                </a>
            </div>



            <div class="mt-2 lg:px-4">
                <a href="{{ route('player.records', ['hash' => $hash]) }}" class="block py-2 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.records')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Records ({{ count($counts['records']) }})
                </a>
            </div>
        </div>
    </div>
</div>