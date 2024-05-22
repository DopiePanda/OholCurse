<div class="pt-20 px-8 pb-8 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
    <div class="text-4xl font-bold text-center text-skin-base dark:text-skin-base-dark">
        Contact records for {{ $leaderboard }}
    </div>
    <div class="mt-4 px-8 grid grid-cols-1 gap-4">
        @foreach ($records as $record)
            <div class="p-2 bg-gray-200">
                <div class="text-xl font-bold capitalize">{{ $record->user->username }}</div>
                <div class="mt-2 p-2 border border-black rounded-lg ">
                    <div class="my-2 flex flex-row justify-between">
                        <div class="text-mg">
                            <span class="font-bold">Nickname:</span><br />{{ $record->nickname }}
                        </div>
                        <div class="text-right capitalize">
                            @if ($record->type == 'friend')
                                <span class="text-green-600">
                            @elseif ($record->type == 'enemy')
                                <span class="text-brown-600">
                            @else
                                <span class="text-orange-600">
                            @endif
                                {{ $record->type }}
                            </span>
                        </div>
                    </div>
                    
                    @if($record->comment)
                        <div class="my-2 italic">
                            <span class="font-bold">Comment:</span><br /> {{ $record->comment }}
                        </div>
                    @endif

                    @if($record->phex_hash)
                        <div class="my-2 italic">
                            Phex hash: {{ $record->phex_hash }}
                        </div>

                        @if($record->phex && $record->phex->px_name)
                            <div class="my-2 italic">
                                Phex name: {{ $record->phex->px_name }}
                            </div>
                        @endif

                        @if($record->olgc && $record->olgc->olgc_name)
                            <div class="my-2 italic">
                                OLGC name: {{ $record->olgc->olgc_name }}
                            </div>  
                        @endif
                        
                    @endif

                    <div class="my-2 text-sm">
                        Updated last / Created at<br/>
                        {{ $record->updated_at }} / {{ $record->created_at }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
