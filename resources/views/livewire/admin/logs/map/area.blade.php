
<div class="w-full mx-auto">
    @push('styles')
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @endpush

    @push('scripts')

        <script style="text/javascript">

            $(document).ready(function () {

                var path = "{{ route('select2.ajax') }}";

                $('#characters').select2({
                    placeholder: 'Search by character name',
                    minimumInputLength: 1,
                    ajax: {
                        url: path,
                        dataType: 'json',
                        delay: 500,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    var datetime = new Date(item.timestamp*1000);
                                    var label = `${item.name.name} - ${item.character_id} (${datetime.toISOString()})`;
                                    return {
                                        text: label,
                                        id: item.character_id
                                    }
                                })
                            };
                        },

                    }
                });

                styleSelectInput("#characters")

                $('#characters').on('change', function (e) {
                    var data = $('#characters').select2("val");
                    @this.setCharacterId(data);
                    console.log('Character ID set:');
                    console.log(data);
                });

                $('#gameObjects').select2({
                });

                styleSelectInput("#gameObjects")

                $('#gameObjects').on('change', function (e) {
                    var data = $('#gameObjects').select2("val");
                    @this.setGameObject(data);
                    console.log('Game object ID set:');
                    console.log(data);
                });

                function styleSelectInput(element)
                {
                    $(element).siblings('.select2').children('.selection').children('.select2-selection').css('background-color', '#1E293B')
                    $(element).siblings('.select2').children('.selection').children('.select2-selection').css('height', '40px')
                    $(element).siblings('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').css('padding-top', '4px')
                    $(element).siblings('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').css('color', '#fff')
                    $(element).siblings('.select2').children('.selection').children('.select2-selection').css('border', 0)
                }
                
            });
        </script>

    @endpush
    <div class="flex flex-row w-full mx-auto">
        <div wire:ignore class="basis-2/3 mx-auto p-4 rounded-lg dark:bg-slate-700">
            <div class="p-2">
                <div wire:ignore class="w-full text-gray-200">
                    <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="characters">Character start point:</label></div>
                    <div id="characterInput"><select id="characters" class="block dark:text-gray-200 dark:bg-gray-700" style="width: 100%; max-width: 100% !important;">
                        
                    </select></div>
                </div>
                <div class="mt-1">
                    <small>
                        Here you select a character ONLY for their birth coordinates. These are the coordinates we will 
                        use as a base, before adding the offsets.
                    </small>
                </div>
                <div>@error('character_start') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div wire:ignore class="w-full text-gray-200">
                    <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="gameObjects">Item to search for:</label></div>
                    <select id="gameObjects" class="block dark:text-gray-200 dark:bg-gray-700" style="width: 100%; max-width: 100% !important;">
                        @foreach($objects as $object)
                            <option class="dark:text-gray-200" @if($object_id == $object->id) selected @endif value="{{ $object->id }}">{{ $object->name }} (ID: {{ $object->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-1">
                    <small>Select the game object you know or believe someone has been interacted with</small>
                </div>
                <div>@error('object_id') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="offset_x">Offset X:</label></div>
                <div><input id="offset_x" wire:model="offset_x" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="400" /></div>
                <div class="mt-1">
                    <small>Here you set the X axis offset (1k tiles west is 1000, 1k tiles east is -1000)</small>
                </div>
                <div>@error('offset_x') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="offset_y">Offset Y:</label></div>
                <div><input id="offset_y" wire:model="offset_y" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="-200" /></div>
                <div class="mt-1">
                    <small>Here you set the Y axis offset (200 tiles north is 200, 200 tiles south is -200)</small>
                </div>
                <div>@error('offset_y') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="radius_size">Radius Size:</label></div>
                <div><input id="radius_size" wire:model="radius_size" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="number" placeholder="400" /></div>
                <div class="mt-1">
                    <small>
                        Here you choose the radius of the search area, so puttig f.ex 100 in here will make the search area 
                        will be a 100 x  100 square in the coordinate area selected above.
                    </small>
                </div>
                <div>@error('radius_size') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="group">Group By Character:</label></div>
                <div><input id="group" wire:model="group" class="rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="checkbox"/></div>
                <div class="mt-1">
                    <small>
                        Enabling this setting will summeraize all actions done with your selected object, in your selected 
                        search area. A seperate column will display the amount of times they interacted with your chosen item.
                    </small>
                </div>
                <div>@error('group') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><button wire:click="getResults" class="p-3 text-white text-center w-full bg-primary-500 dark:bg-primary-500" type="button">Fetch results</button></div>
            </div>
        </div>
        <div class="ml-4 px-6 py-6 basis-1/3 bg-white dark:bg-slate-700 justify-between rounded-md text-gray-800 dark:text-gray-400">
            <div class="text-2xl pt-4">Distance calculations</div>
            @if($results)
            <div class="h-full flex flex-col justify-start w-full ">
                <div class="py-2 flex flex-col">
                    <div class="p-1 border-b border-gray-400">
                        <div>Birth X: {{ $birth_x }}</div>
                    </div>
                    <div class="p-1 border-b border-gray-400">
                        <div>Birth Y: {{ $birth_y }}</div>
                    </div>
                </div>
                <div class="py-2 flex flex-col">
                    <div class="p-1 border-b border-gray-400">
                        <div>Min X: {{ $x_min }}</div>
                    </div>
                    <div class="p-1 border-b border-gray-400">
                        <div>Max X: {{ $x_max }}</div>
                    </div>
                </div>
                <div class="py-2 flex flex-col">    
                    
                    <div class="p-1 border-b border-gray-400">
                        <div>Min Y: {{ $y_min }}</div>
                    </div>
                    <div class="p-1 border-b border-gray-400">
                        <div>Max Y: {{ $y_max }}</div>
                    </div>
                </div>
                <div class="py-2 flex flex-col">    
                    <div class="p-1">
                        <div>Total results: {{ count($results) }}</div>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>

    <div wire:loading class="w-full mx-auto text-center mt-12 text-primary-500">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <div>Searching for map logs for object interactions</div>
        <div class="text-gray-400 text-sm">You cna also group by character to get summarized object interactions</div>
    </div>

    <div class="py-6 w-full mx-auto">
        @if( $results )
        <table wire:loading.remove class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
            <thead>
                <tr class="border-b border-gray-400">
                    <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-5000 dark:border-primary-700">#</td>
                    <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-500 dark:border-primary-700">Leaderboard</td>
                    <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-500 dark:border-primary-700">Character</td>
                    <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-500 dark:border-primary-700">Position</td>
                    <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-500 dark:border-primary-700">Object</td>
                    @if($this->group)
                        <td class="p-4 bg-primary-500 text-white border-r border-primary-700 dark:bg-primary-500 dark:border-primary-700">Count</td>
                    @endif
                    <td class="p-4 bg-primary-500 text-white dark:bg-primary-500 dark:border-primary-700">Date</td>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $log)
                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                        <td class="p-1 border border-gray-400">{{ $loop->index }}</td>
                        <td class="p-1 border border-gray-400">
                            @if(isset($log->life->leaderboard->leaderboard_name)) 
                                <a class="text-white font-bold dark:text-white" href="{{ route('player.curses', ['hash' => $log->life->leaderboard->player_hash]) }}">
                                    {{ $log->life->leaderboard->leaderboard_name }}
                                </a>
                            @else
                                <span title="Check again after 9AM tomorrow for updated data">
                                    - LEADERBOARD MISSING -
                                </span>
                            @endif
                        </td>
                        <td class=" p-1 border border-gray-400">
                            <div class="mt-1 text-sm text-black lowercase capitalize dark:text-gray-200">
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
                                    <div>Object ID: {{ $log->object_id }}</div>
                                @endif
                            </div>
                            <div>{{ $log->object->name ?? 'Name missing' }}</div>
                        </td>
                        @if($this->group)
                            <td class="p-1 border border-gray-400 text-sm">
                                <div>{{ $log->count ?? 0 }}</div>
                            </td>
                        @endif
                        <td class="p-1 border border-gray-400">
                            @if(isset($log->timestamp))
                                {{ date('Y-m-d H:i', $log->timestamp) }}
                            @else
                                <span title="Check again after 9AM tomorrow for updated data">
                                    (- DATE MISSING -)
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <span class="text-gray-800 dark:text-gray-400">Nothing here.</span>
                @endforelse
            </tbody>
        </table>
        @endif
    </div>
</div>