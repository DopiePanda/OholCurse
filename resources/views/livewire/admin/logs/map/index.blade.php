@section('before-head-end')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

<div class="w-full lg:w-2/3">
    <div class="text-2xl text-center">Map Log Checker</div>
    <div class="mt-6 mx-auto w-full lg:w-2/3 py-1">
        <div class="grid grid-cols-2 p-2 w-full">
            <div class="px-4">
                <div class="">Game Object:</div>
                <div wire:ignore class="w-1/2">
                    <select wire:model.lazy="selected_object" id="selectedObject" class="w-full block" style="min-width:100% !important; max-width: 96px !important;">
                        
                        @foreach($objects as $object)
                            <option value="{{ $object->id ? $object->id : '0' }}" >{{ $object->name }} (ID: {{ $object->id ? $object->id : 'f' }})</option>
                        @endforeach
                    </select>
                    <span wire:click="setGameObject('null')" class="text-xs cursor-pointer">reset</span>
                </div>
            </div>
            <div class="px-4">
                <div>Character name/ID:</div>
                <div wire:ignore class="w-1/2">
    
                    <select wire:model.lazy="selected_character" id="selectedCharacter" class="w-full block" style="min-width:100% !important; max-width: 96px !important;">
                        <option class="" value="" >Enter character name or ID</option>
                    </select>
                    <span wire:click="resetField('character')" class="text-xs cursor-pointer">reset</span>
                </div>
            </div>
        </div>
        <div class="overflow-x-scroll py-6 max-w-screen mx-auto sm:px-6 lg:px-8">
            @if( $results )
            <div class="text-right text-lg">Amount of results: {{ count($results) }}</div>
            <table wire:loading.remove class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
                <thead>
                    <tr class="border-b border-gray-400">
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">#</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Leaderboard</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Character</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Position</td>
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Object</td>
                        <td class="p-4 bg-blue-400 text-white">Date</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $log)
                        <tr class="bg-white">
                            <td class="p-1 border border-gray-400">{{ (count($results) - $loop->index) }}</td>
                            <td class="p-1 border border-gray-400">
                                @if(isset($log->life->leaderboard->leaderboard_name)) 
                                    <a class="text-blue-400 font-semibold" href="{{ route('player.curses', ['hash' => $log->life->leaderboard->player_hash]) }}">
                                        {{ $log->life->leaderboard->leaderboard_name }}
                                    </a>
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        - LEADERBOARD MISSING -
                                    </span>
                                @endif
                            </td>
                            <td class=" p-1 border border-gray-400">
                                <div class="mt-1 text-sm text-black lowercase capitalize">
                                    @if(isset($log->name->name)) 
                                        {{ $log->name->name }} 
                                    @else
                                        <span title="Check again after 9AM tomorrow for updated data">
                                            (- NAME MISSING -)
                                        </span>
                                    @endif 
                                </div>
                            </td>
                            <td class="p-1 border border-gray-400 text-sm">
                                <div>
                                    <a target="_blank" href="https://onemap.wondible.com/#x={{ $log->pos_x }}&y={{ $log->pos_y }}&z=29&s=17&t={{ time() }}">
                                        {{ $log->pos_x ?? 0 }} / {{ $log->pos_y ?? 0 }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-1 border border-gray-400 text-sm font-bold text-center">
                                <div>
                                    @if(strlen($log->object_id) <= 4)
                                        <img class="mx-auto h-6" src="{{ 'https://onetech.info/static/sprites/obj_'.$log->object_id.'.png' }}" alt="" />
                                    @else
                                        {{ $log->object_id }}
                                    @endif
                                </div>
                                <div>{{ $log->object->name ?? 'Name missing' }} (Use: {{ $log->object->use ?? '0' }})</div>
                            </td>
                            <td class="p-1 border border-gray-400">
                                @if(isset($log->timestamp))
                                    {{ date('Y-m-d H:i:s', $log->timestamp) }}
                                @else
                                    <span title="Check again after 9AM tomorrow for updated data">
                                        (- DATE MISSING -)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
            @endif
        </div>
    </div>
    </div>
</div>

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#selectedObject').select2({
                width: "style"
            });
            $('#selectedObject').on('change', function (e) {
                var data = $('#selectedObject').select2("val");
                @this.setGameObject(data);
            });
            /*
            $('#selectedCharacter').select2();
            $('#selectedCharacter').on('change', function (e) {
                var data = $('#selectedCharacter').select2("val");
                @this.setLifeId(data);
            });
            */
        });
    </script>
    <!--
    <script>
        Livewire.on('objectSelected', postId => {
            jQuery(document).ready(function () {
                $('#selectedCharacter').select2();
                $('#selectedCharacter').on('change', function (e) {
                    var data = $('#selectedCharacter').select2("val");
                    @this.set('selected_character', data);
                });
            });
        })
    </script>
    -->

    <script>
        $('#selectedCharacter').select2({
            placeholder: "Choose character...",
            minimumInputLength: 2,
            ajax: {
                url: '/lives/find',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#selectedCharacter').on('change', function (e) {
                var data = $('#selectedCharacter').select2("val");
                @this.setLifeId(data);
            });
    </script>

@endpush