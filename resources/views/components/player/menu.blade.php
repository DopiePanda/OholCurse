<div class="-mt-6 flex flex-col p-2 bg-blue-400 dark:bg-transparent">
    <div class="text-center">
        <div class="p-2 flex flex-col lg:flex-row lg:justify-center">
            <div class="w-full sm:my-2 lg:mx-2 py-2 px-2">
                <a href="{{ route('player.curses', ['hash' => $hash]) }}" class="block w-full mx-2 py-2 px-6 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.curses')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Interactions
                </a>
            </div>
            <div class="w-full sm:my-2 lg:mx-2 pr-3 lg:pr-6 pl-2 py-2 lg:border-x lg:border-gray-200 dark:lg:border-gray-700">
                <a href="{{ route('player.lives', ['hash' => $hash]) }}" class="block w-full mx-2 py-2 px-6 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.lives')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Lives
                </a>
            </div>
            <div class="w-full sm:my-2 lg:mx-2 px-2 py-2">
                <a href="{{ route('player.reports', ['hash' => $hash]) }}" class="block w-full mx-2 py-2 px-6 rounded-xl border border-white dark:border-red-600 @if(request()->routeIs('player.reports')) bg-white dark:bg-red-600 text-gray-800 dark:text-white border-0 @else text-white dark:text-red-600 @endif">
                    Reports
                </a>
            </div>
        </div>
    </div>
</div>