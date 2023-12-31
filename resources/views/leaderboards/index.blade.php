<x-app-layout>

    @section("page-title")- Leaderboards @endsection

    <x-slot name="header">
        <h2 class="text-4xl text-gray-800 leading-tight break-all text-center dark:text-gray-200">
            Leaderboards
        </h2>

    </x-slot>
    <div class="py-1">

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-7xl flex flex-col lg:flex-row grap-4 text-center">
                <a href="{{ route('leaderboards.daily') }}">
                    <div class="border border-blue-400 rounded-xl p-2 mx-6 my-2 grow hover:bg-blue-400 hover:text-white transition-colors dark:border-0 dark:bg-slate-700 dark:hover:bg-slate-600">
                        <img class="mx-auto w-full" src="{{ asset('assets/leaderboard/daily.png') }}" />
                        <div class="text-xl font-bold uppercase dark:text-gray-200">Daily</div>
                    </div>
                </a>

                <a href="{{ route('leaderboards.weekly') }}">
                    <div class="border border-blue-400 rounded-xl p-2 mx-6 my-2 grow hover:bg-blue-400 hover:text-white transition-colors dark:border-0 dark:bg-slate-700 dark:hover:bg-slate-600">
                        <img class="mx-auto w-full" src="{{ asset('assets/leaderboard/weekly.png') }}" />
                        <div class="text-xl font-bold uppercase dark:text-gray-200">Weekly</div>
                    </div>
                </a>

                <a href="{{ route('leaderboards.all-time') }}">
                    <div class="border border-blue-400 rounded-xl p-2 mx-6 my-2 grow hover:bg-blue-400 hover:text-white transition-colors dark:border-0 dark:bg-slate-700 dark:hover:bg-slate-600">
                        <img class="mx-auto w-full" src="{{ asset('assets/leaderboard/all-time.png') }}" />
                        <div class="text-xl font-bold uppercase dark:text-gray-200">All time</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>