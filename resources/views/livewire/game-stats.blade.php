<div class="w-full md:w-3/4 lg:max-w-6xl">

    @section("page-title")
        Game Statistics
    @endsection

    <div class="w-full lg:grow lg:max-w-6xl flex flex-col">

        <div class="bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark mt-6 p-2 lg:p-6">

            <div class="text-4xl mt-2 text-center text-skin-base dark:text-skin-base-dark">Game Statistic</div>

            <!-- Player data -->

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users fa-2xl"></i>
                    </div>
                    <div>Total lives played</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ number_format(count($lives), 0, ',', ' ') }}</div>
                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                        {{ $lives->first()->created_at->format('Y-m-d') }}
                        <div>{{ $lives->sortByDesc('id')->first()->created_at->format('Y-m-d') }}</div>
                    </div>
                </div>

                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>Total minutes played</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ number_format($minutes_played, 0, ',', ' ') }}</div>
                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                        Total hours
                        <div>{{ number_format(($minutes_played / 60), 0, ',', ' ') }}</div>
                    </div>
                </div>

                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>Unique players</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ number_format($accounts, 0, ',', ' ') }}</div>
                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                        {{ $lives->first()->created_at->format('Y-m-d') }}
                        <div>{{ $lives->sortByDesc('id')->first()->created_at->format('Y-m-d') }}</div>
                    </div>
                </div>
            </div>

            <!-- Eve data -->

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users fa-2xl"></i>
                    </div>
                    <div>Eves born</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ number_format(count($eves), 0, ',', ' ') }}</div>
                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                        {{ $lives->first()->created_at->format('Y-m-d') }}
                        <div>{{ $lives->sortByDesc('id')->first()->created_at->format('Y-m-d') }}</div>
                    </div>
                </div>

                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>Eve movement west</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ $eve_movement }}</div>
                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                        From: X{{ $eve_first->pos_x }}
                        <div>To: X{{ $eve_last->pos_x }}</div>
                    </div>
                </div>
            </div>
        </div>
        @php
                $time_end = microtime(true);
        
                $end = $time_end - $time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
    </div>
</div>