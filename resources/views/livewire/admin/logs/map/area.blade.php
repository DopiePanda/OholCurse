<div class="w-full lg:w-2/3">
    <div class="w-full lg:w-1/3 mx-auto">
        <div wire:ignore class="mx-auto p-4 rounded-lg dark:bg-slate-700">
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="character_start">Character ID Start Point:</label></div>
                <div><input wire:model="character_start" style="color:#333;" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="6739939" /></div>
                <div>@error('character_start') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="object_id">Game Object ID:</label></div>
                <div><input wire:model="object_id" style="color:#333;" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="1268" /></div>
                <div>@error('object_id') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="offset_x">Offset X:</label></div>
                <div><input wire:model="offset_x" style="color:#333;" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="400" /></div>
                <div>@error('offset_x') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>
            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="offset_y">Offset Y:</label></div>
                <div><input wire:model="offset_y" style="color:#333;" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" placeholder="-200" /></div>
                <div>@error('offset_y') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="radius_size">Radius Size:</label></div>
                <div><input wire:model="radius_size" style="color:#333;" class="w-full rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="number" placeholder="400" /></div>
                <div>@error('radius_size') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><label class="text-sm font-semibold text-gray-800 dark:text-gray-400" for="radius_size">Group By Character:</label></div>
                <div><input wire:model="group" style="color:#333;" class="rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="checkbox"/></div>
                <div>@error('group') <div class="mt-1 text-red-400 font-semibold text-sm italic">{{ $message }}</div> @enderror</div>
            </div>

            <div class="p-2">
                <div><button wire:click="getResults" class="p-3 text-white text-center w-full bg-blue-400 rounded-lg dark:bg-red-500" type="button">Fetch results</button></div>
            </div>
        </div>

        @if($results)
            <div class="mt-4 min-w-1/3 p-4 border border-gray-400 rounded-md text-gray-800 dark:text-gray-400">
                <div class="grid grid-cols-2">
                    <div class="p-2 border-b border-r border-gray-400">Birth X: {{ $birth_x }}</div>
                    <div class="p-2 border-b border-gray-400">Birth Y: {{ $birth_y }}</div>
                    <div class="p-2 border-b border-r border-gray-400">Min X: {{ $x_min }}</div>
                    <div class="p-2 border-b border-gray-400">Min Y: {{ $y_min }}</div>
                    <div class="p-2 border-b border-r border-gray-400">Max X: {{ $x_max }}</div>
                    <div class="p-2 border-b border-gray-400">Max Y: {{ $y_max }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="overflow-x-scroll py-6 max-w-screen mx-auto sm:px-6 lg:px-8">
        @if( $results )
        <div class="text-right text-lg text-gray-800 dark:text-gray-400">Amount of results: {{ count($results) }}</div>
        <table wire:loading.remove class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
            <thead>
                <tr class="border-b border-gray-400">
                    <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">#</td>
                    <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">Leaderboard</td>
                    <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">Character</td>
                    <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">Position</td>
                    <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">Object</td>
                    @if($this->group)
                        <td class="p-4 bg-blue-400 text-white border-r border-blue-500 dark:bg-red-500 dark:border-red-600">Count</td>
                    @endif
                    <td class="p-4 bg-blue-400 text-white dark:bg-red-500 dark:border-red-600">Date</td>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $log)
                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-600 dark:odd:bg-slate-500 dark:text-gray-300">
                        <td class="p-1 border border-gray-400">{{ $loop->index }}</td>
                        <td class="p-1 border border-gray-400">
                            @if(isset($log->life->leaderboard->leaderboard_name)) 
                                <a class="text-blue-400 font-semibold dark:text-red-400" href="{{ route('player.curses', ['hash' => $log->life->leaderboard->player_hash]) }}">
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
                                {{ date('Y-m-d H:i:s', $log->timestamp) }}
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
