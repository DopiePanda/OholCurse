@section('before-head-end')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
<div class="w-full">
    @section("page-title")- Daily leaderboards @endsection

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-6 text-3xl font-bold text-center dark:text-gray-200">
            Search the daily leaderboards
        </div>
        <div class="mt-1 mb-6 text-lg uppercase font-bold text-center dark:text-gray-400">
            Period: {{ $start }} to {{ $end }}
        </div>

        <div class="grid grid-cols-2 items-center">
            <div wire:ignore class="max-w-96">
                <select id="gameObjects" class="block" style="width:24rem !important; max-width: 96px !important;">
                    @foreach($objects as $object)
                        <option value="{{ $object->id }}" @if($object->id == session('object')) selected @endif>{{ $object->name }} (ID: {{ $object->id }})</option>
                    @endforeach
                </select>
            </div>
            <div wire:ignore class="text-right dark:text-gray-400">Show: 
                <select id="resultLimit">
                    <option value="10" @if(session('limit') == 10) selected @endif>10</option>
                    <option value="25" @if(session('limit') == 25) selected @endif>25</option>
                    <option value="50" @if(session('limit') == 50) selected @endif>50</option>
                    <option value="100" @if(session('limit') == 100) selected @endif>100</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-scroll">
            <div class="w-full my-2 text-center" wire:loading.delay>
                <img class="block mx-auto"
                    src="{{ asset('assets/loader/spinner.gif') }}" 
                    alt="oholcurse-logo" />
            </div>

            <table wire:loading.remove class="mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
                <thead>
                    <tr class="border-b border-gray-400">
                        <td class="p-2 border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Place</td>
                        <td class="p-2 border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Score</td>
                        <td class="p-2 border-r border-gray-400 bg-skin-fill dark:bg-skin-fill-dark">Leaderboard name</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark">Character name (Character ID)</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @if($loop->index == 0)    
                            <tr class="bg-green-500 border-b border-green-700">
                        @elseif($loop->index == 1)
                            <tr class="bg-lime-500 border-b border-lime-700">
                        @elseif($loop->index == 2)
                            <tr class="bg-yellow-500 border-b border-yellow-700">
                        @else
                            <tr class="bg-gray-200 border-b border-gray-400">  
                        @endif
                            <td class="p-2">{{ $loop->index+1 }}</td>
                            <td class="p-2">{{ $result->count }}</td>
                            <td class="p-2">
                                @if(isset($result->life->leaderboard->player_hash) && isset($result->life->leaderboard->leaderboard_name)) 
                                <a href="{{ route('player.interactions', ['hash' => $result->life->leaderboard->player_hash]) }}">
                                    {{ $result->life->leaderboard->contact->nickname ?? $result->life->leaderboard->leaderboard_name }}
                                </a>
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        - LEADERBOARD MISSING -
                                    </span>
                                @endif
                            </td>
                            <td class="p-2">
                                @if(count($result->lives) == 2)
                                    @if(($result->lives[1]->timestamp - $result->lives[0]->timestamp) > 3600)
                                        <span><i class="fa-solid fa-ghost text-gray-200 dark:text-gray-600 pr-1"></i></span>
                                    @endif
                                @endif
                                @if(isset($result->name->name)) 
                                    {{ $result->name->name }} 
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- NAME MISSING -)
                                    </span>
                                @endif 
                                @if(isset($result->character_id))
                                    ({{ $result->character_id }})
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- LIFE ID MISSING -)
                                    </span>
                                @endif
                                </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
        @php
            $time_end = microtime(true);
    
            $end = $time_end - $time_start;
        @endphp
        <div class="text-center text-sm mt-2 text-gray-400">Page load time: {{ round($end, 3) }}s</div>
    </div>
</div>

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#gameObjects').select2({
                width: "style"
            });
            $('#gameObjects').on('change', function (e) {
                var data = $('#gameObjects').select2("val");
                @this.setGameObject(data);
            });

            $('#resultLimit').select2();
            $('#resultLimit').on('change', function (e) {
                var data = $('#resultLimit').select2("val");
                @this.setResultLimit(data);
            });
        });
    </script>

@endpush