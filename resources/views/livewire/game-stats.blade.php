<div class="w-full md:w-3/4 lg:max-w-6xl">

    @section("page-title")
        Game Statistics
    @endsection

    <div class="w-full lg:grow lg:max-w-6xl flex flex-col">

        <div class="text-xl text-gray-400 text-center">
            <div class="font-bold">
                Game statistics for last {{ $days }} days
            </div>
            <div class="italic">
                {{ now()->format('Y-m-d') }} to {{ now()->subDays($days)->format('Y-m-d') }}
            </div>
        </div>

        <div class="bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark mt-6 p-2 lg:p-6">
            <!-- Player data -->
            <div class="text-5xl mt-2 text-center text-skin-base dark:text-skin-base-dark">Player Statistics</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 place-items-center">
                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-clock fa-2xl"></i>
                    </div>
                    <div>Minutes played</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($deaths->minutes_played, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-users fa-2xl"></i>
                    </div>
                    <div>Lives played</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($deaths->lives_played, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-user fa-2xl"></i>
                    </div>
                    <div>Unique accounts</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($deaths->accounts, 0, ',', ' ') }}
                    </div>
                </div>
            </div>

            <!-- Eve data -->
            <div class="text-5xl mt-6 mb-4 text-center text-skin-base dark:text-skin-base-dark">Eve Statistics</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 place-items-center">
                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-person-breastfeeding fa-2xl"></i>
                    </div>
                    <div>Eves born</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($eves->eve_count, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-tent-arrow-turn-left fa-2xl"></i>
                    </div>
                    <div>Eve movement west</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ ($eves->eve_pos_first - $eves->eve_pos_last) }}
                    </div>
                </div>
            </div>

            <!-- Family data -->
            <div class="text-5xl mt-6 mb-4 text-center text-skin-base dark:text-skin-base-dark">Family Statistics</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 place-items-center">
                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-snowflake fa-2xl"></i>
                    </div>
                    <div>Arctic lives</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->fam_arctic, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-earth-americas fa-2xl"></i>
                    </div>
                    <div>Language lives</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->fam_language, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-pepper-hot fa-2xl"></i>
                    </div>
                    <div>Jungle lives</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->fam_jungle, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-horse-head fa-2xl"></i>
                    </div>
                    <div>Desert lives</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->fam_desert, 0, ',', ' ') }}
                    </div>
                </div>
            </div>

            <!-- Gender data -->
            <div class="text-5xl mt-6 mb-4 text-center text-skin-base dark:text-skin-base-dark">Gender Statistics</div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 place-items-center">
                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-venus fa-2xl"></i>
                    </div>
                    <div>Females born</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->females, 0, ',', ' ') }}
                    </div>
                </div>

                <div class="w-full h-48 place-content-center p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                    <div class="mt-2 h-12 mx-auto text-center">
                        <i class="fa-solid fa-mars fa-2xl"></i>
                    </div>
                    <div>Males born</div>
                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">
                        {{ number_format($lives->males, 0, ',', ' ') }}
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