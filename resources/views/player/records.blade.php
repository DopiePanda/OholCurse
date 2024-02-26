<x-app-layout>

    <x-effects.backgrounds.animated-background :donator="$donator" />

    @section("page-title")
        @if( $player )- {{ $player->leaderboard_name }}'s Records @else- Records @endif
    @endsection

    <x-slot name="header">
        <livewire:player.profile-header :hash="$hash">
    </x-slot>

    <div class="w-full lg:grow lg:max-w-6xl flex flex-col">

        <livewire:player.profile-menu :hash="$hash">

        <div class="bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark mt-6 p-2 lg:p-6">

            <div class="text-4xl mt-2 text-center text-skin-base dark:text-skin-base-dark">Attained leaderboard records</div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                @forelse ($records as $record)
                    @if($record->currentRecord->amount > $record->amount)
                        @if($record->currentRecord->leaderboard_id == $record->leaderboard_id)
                            <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                                <div class="h-12 mx-auto text-center">
                                    <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                                </div>
                                <div>{{ $record->currentRecord->leaderboard->label }}</div>
                                <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ $record->currentRecord->amount }}</div>
                                <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                    {{ ucwords(strtolower($record->currentRecord->lifeName->name)) }}
                                    <div>{{ date('Y-m-d H:i:s', $record->currentRecord->timestamp) }}</div>
                                </div>
                            </div>
                        @else
                            <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-dashed border-gray-400 text-center">
                                <div class="h-12 mx-auto text-center">
                                    <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                                </div>
                                <div class="line-through">{{ $record->leaderboard->label }}</div>
                                <div class="my-2 text-3xl text-skin-muted dark:text-skin-muted-dark">
                                    <span class="line-through">
                                        {{ $record->amount }}
                                    </span> 
                                    <span> > </span> 
                                    <span class="text-skin-base dark:text-skin-base-dark">
                                        @if($record->currentRecord->player->player_hash)
                                        <a href="{{ route('player.records', ['hash' => $record->currentRecord->player->player_hash]) }}" title="{{ $record->currentRecord->player->leaderboard_name ?? 'Leaderboard name missing' }}">
                                            {{ $record->currentRecord->amount }}
                                        </a>
                                        @else
                                            {{ $record->currentRecord->amount }}
                                        @endif
                                    </span>
                                </div>
                                <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                    {{ ucwords(strtolower($record->lifeName->name)) }}
                                    <div>{{ date('Y-m-d H:i:s', $record->timestamp) }}</div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                            <div class="h-12 mx-auto text-center">
                                <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                            </div>
                            <div>{{ $record->leaderboard->label }}</div>
                            <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ $record->amount }}</div>
                            <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                {{ ucwords(strtolower($record->lifeName->name)) }}
                                <div>{{ date('Y-m-d H:i:s', $record->timestamp) }}</div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-1 md:col-span-3 lg:col-span-4 text-center text-gray-400 dark:text-gray-400">
                        This player have not yet attained any leaderboard placements
                    </div>
                @endforelse
            </div>

            <div class="text-4xl mt-8 text-center text-skin-base dark:text-skin-base-dark">Ghost leaderboard records</div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                @forelse ($ghostRecords as $record)
                    @if($record->currentGhostRecord->amount > $record->amount)
                            @if($record->currentGhostRecord->leaderboard_id == $record->leaderboard_id)
                                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                                    <div class="h-12 mx-auto text-center">
                                        <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                                    </div>
                                    <div>{{ $record->currentGhostRecord->leaderboard->label }}</div>
                                    <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ $record->currentGhostRecord->amount }}</div>
                                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                        {{ ucwords(strtolower($record->currentGhostRecord->lifeName->name)) }}
                                        <div>{{ date('Y-m-d H:i:s', $record->currentGhostRecord->timestamp) }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-dashed border-gray-400 text-center">
                                    <div class="h-12 mx-auto text-center">
                                        <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                                    </div>
                                    <div class="line-through">{{ $record->leaderboard->label }}</div>
                                    <div class="my-2 text-3xl text-skin-muted dark:text-skin-muted-dark">
                                        <span class="line-through">
                                            {{ $record->amount }}
                                        </span> 
                                        <span> > </span> 
                                        <span class="text-skin-base dark:text-skin-base-dark">
                                            @if($record->currentGhostRecord->player->player_hash)
                                            <a href="{{ route('player.records', ['hash' => $record->currentGhostRecord->player->player_hash]) }}" title="{{ $record->currentGhostRecord->player->leaderboard_name ?? 'Leaderboard name missing' }}">
                                                {{ $record->currentGhostRecord->amount }}
                                            </a>
                                            @else
                                                {{ $record->currentGhostRecord->amount }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                        {{ ucwords(strtolower($record->lifeName->name)) }}
                                        <div>{{ date('Y-m-d H:i:s', $record->timestamp) }}</div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="p-2 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark text-center">
                                <div class="h-12 mx-auto text-center">
                                    <img class="h-12 w-auto mx-auto" src="{{ asset($record->leaderboard->image) ?? '' }}" alt="{{ $record->leaderboard->label ?? '' }}" title="{{ $record->leaderboard->label ?? '' }}">
                                </div>
                                <div>{{ $record->leaderboard->label }}</div>
                                <div class="my-2 text-3xl text-skin-base dark:text-skin-base-dark">{{ $record->amount }}</div>
                                <div class="p-1 mt-3 rounded-full bg-skin-fill dark:bg-skin-fill-dark text-white text-sm">
                                    {{ ucwords(strtolower($record->lifeName->name)) }}
                                    <div>{{ date('Y-m-d H:i:s', $record->timestamp) }}</div>
                                </div>
                            </div>
                        @endif
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