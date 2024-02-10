<div>
    <div class="p-2 pt-2">
        <section class="accordion border border-skin-base dark:border-skin-base-dark rounded-lg">
            <input type="checkbox" name="collapse{{ $handle }}" id="handle{{ $handle }}">
            <h2 class="handle text-white bg-skin-fill dark:bg-skin-fill-dark">
                <label for="handle{{ $handle }}">{{ $title }} ({{ $interactions->total() }})</label>
            </h2>
            <div class="content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                    @forelse($interactions as $line)
                        <div class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark mt-1 p-2 rounded-lg">
                            <div>
                                <div class="mb-2 text-md font-bold text-skin-muted dark:text-skin-muted-dark">Recieved from:</div> 

                                <a href="{{ route('player.interactions', ['hash' => $line->player_hash ?? 'error']) }}" title="Sender was {{ $line->name->name ?? $line->character_id }}"> 
                                    @if(isset($line->player_hash))
                                        <div class="break-all text-skin-base font-bold dark:text-skin-base-dark">

                                            @if($line->contact_recieved)
                                                <span>
                                                    @if($line->contact_recieved->type == 'friend')
                                                        <span class="text-green-600 dark:text-green-400">
                                                            <i class="fas fa-heart"></i>
                                                            {{ $line->contact_recieved->nickname }}
                                                        </span>
                                                    @elseif($line->contact_recieved->type == 'dubious')
                                                        <span class="text-orange-600 dark:text-orange-400">
                                                            <i class="fas fa-question"></i>
                                                            {{ $line->contact_recieved->nickname }}
                                                        </span>
                                                    @else
                                                        <span class="text-amber-700">
                                                            <i class="fas fa-poop"></i>
                                                            {{ $line->contact_recieved->nickname }}
                                                        </span>
                                                    @endif
                                                </span>
                                            @elseif($line->leaderboard_recieved)
                                                {{  $line->leaderboard_recieved->leaderboard_name }}
                                            @else
                                                <span title="Leaderboard name missing" class="text-gray-800 dark:text-gray-200">
                                                    <i>{{  $line->name->name ?? 'Leaderboard missing' }}</i>
                                                </span>
                                            @endif

                                            <div class="text-gray-700 text-sm inline-block">
                                                (<span class="text-green-600">{{ $line->scores_recieved ? round($line->scores_recieved->gene_score, 0) : 0; }}</span>
                                                    /
                                                <span class="text-red-600">{{ $line->scores_recieved ? $line->scores_recieved->curse_score : 0; }}</span>)
                                            </div>
                                        </div>
                                        <div class="break-all text-xs dark:text-gray-600">{{ $line->player_hash }}</div>
                                    @else
                                        {{ $line->character_id }}
                                    @endif
                                </a>
                            </div>
                            <div class="mt-2 text-sm dark:text-gray-400">{{ \Carbon\Carbon::parse($line->timestamp)->format('Y-m-d (H:i:s)') }}</div>
                        </div>
                    @empty
                        <span>No {{ strtolower($title) }}</span>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $interactions->links(data: ['scrollTo' => false]) }}
                </div>

            </div>
        </section>
    </div>
</div>