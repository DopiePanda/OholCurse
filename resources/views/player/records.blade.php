<x-app-layout>

    @section("page-title")
        @if( $player )
            - {{ $player->leaderboard_name }}'s Records
        @else
            - Records
        @endif
    @endsection

    <x-slot name="header">
        <livewire:player.profile-header :hash="$hash">
    </x-slot>

    <div class="w-full lg:grow lg:max-w-5xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">

        <div class="mt-6 bg-gray-200 p-2 lg:p-6 dark:bg-slate-700">

            <div class="text-4xl mt-2 text-center text-blue-400 dark:text-red-500">Attained leaderboard records</div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($records as $record)
                    @foreach ($maxRecords as $max)
                        @if ($max->object_id == $record->object_id)
                            <div class="p-2 text-center rounded-lg border @if($max->max_amount > $record->max_amount) bg-gray-400 text-gray-800 dark:bg-gray-800 border-gray-400 dark:border-gray-500 dark:text-gray-400  @else border-blue-400 dark:border-red-500 dark:text-gray-400 @endif">
                                <div class="h-20 mx-auto text-center">
                                    <img class="h-20 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="" title="{{ asset($record->leaderboard->image) ?? '' }}">
                                </div>
                                <div>
                                    {{ $record->leaderboard->label }}
                                </div>
                                <div class="text-2xl font-bold dark:text-red-500">             
                                    @if($max->max_amount > $record->max_amount)
                                        <div class="text-gray-600 decoration-4 line-through decoration-gray-800">
                                            <span class="">
                                                {{ $record->max_amount }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-blue-400 dark:text-red-500">
                                            {{ $record->max_amount }}
                                            @if($record->player->leaderboard_id == 53)
                                                <div class="text-center">
                                                    <img title="Second place paricipation award" class="mx-auto h-6" src="{{ asset('assets/leaderboard/participation-award.png') }}" />
                                                </div>
                                            @endif
                                        </span>
                                    @endif      
                                </div>
                                <div class="p-1 mt-3 rounded-full bg-white dark:bg-gray-600 text-slate-900 text-sm font-semibold italic">
                                    <span class="">
                                        {{ $record->lifeName->name ?? 'missing' }}
                                        @if ($record->ghost)
                                            <i class="ml-2 fa-solid fa-ghost text-gray-400 dark:text-gray-400 pr-1" title="OBTAINED AS GHOST"></i>
                                        @endif
                                    </span>
                                </div>
                                <div class="mt-3 text-sm italic">
                                    {{ date('Y-m-d H:i:s', $record->timestamp) }}
                                </div>
                            </div>
                        @else

                        @endif        
                    @endforeach
                @empty
                    <div class="col-span-1 md:col-span-3 lg:col-span-4 text-center text-gray-400 dark:text-gray-400">
                        This player have not yet attained any leaderboard placements
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-4xl mt-2 text-center text-blue-400 dark:text-red-500">Ghost leaderboard records</div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($ghostRecords as $record)
                    @foreach ($maxRecordsGhosts as $max)
                        @if ($max->object_id == $record->object_id)
                            <div class="p-2 text-center rounded-lg border @if($max->max_amount > $record->max_amount) bg-gray-400 text-gray-800 dark:bg-gray-800 border-gray-400 dark:border-gray-500 dark:text-gray-400  @else border-blue-400 dark:border-red-500 dark:text-gray-400 @endif">
                                <div class="h-20 mx-auto text-center">
                                    <img class="h-20 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="" title="{{ asset($record->leaderboard->image) ?? '' }}">
                                </div>
                                <div>
                                    {{ $record->leaderboard->label }}
                                </div>
                                <div class="text-2xl font-bold dark:text-red-500">             
                                    @if($max->max_amount > $record->max_amount)
                                        <div class="text-gray-600 decoration-4 line-through decoration-gray-800">
                                            <span class="">
                                                {{ $record->max_amount }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-blue-400 dark:text-red-500">
                                            {{ $record->max_amount }}
                                        </span>
                                        @if($record->player->leaderboard_id == 53)
                                            <div class="text-center">
                                                <img title="Second place paricipation award" class="mx-auto h-6" src="{{ asset('assets/leaderboard/participation-award.png') }}" />
                                            </div>
                                        @endif
                                    @endif      
                                </div>
                                <div class="p-1 mt-3 rounded-full bg-white dark:bg-gray-600 text-slate-900 text-sm font-semibold italic">
                                    <span class="">
                                        {{ $record->lifeName->name ?? 'missing' }}
                                        @if ($record->ghost)
                                            <i class="ml-2 fa-solid fa-ghost text-gray-400 dark:text-gray-400 pr-1" title="OBTAINED AS GHOST"></i>
                                        @endif
                                    </span>
                                </div>
                                <div class="mt-3 text-sm italic">
                                    {{ date('Y-m-d H:i:s', $record->timestamp) }}
                                </div>
                            </div>
                        @else

                        @endif        
                    @endforeach
                @empty
                    <div class="col-span-1 md:col-span-3 lg:col-span-4 text-center text-gray-400 dark:text-gray-400">
                        This player have not yet attained any ghost leaderboard placements
                    </div>
                @endforelse
            </div>
        </div>
        @php
                $time_end = microtime(true);
        
                $end = $time_end - $time;
            @endphp
            <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
    </div>
</x-app-layout>