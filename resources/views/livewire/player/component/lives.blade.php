<div class="py-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl">
    <div class="flex flex-row gap-4 px-4 py-2">
        <div>
            <div class="uppercase text-sm font-bold mb-1 text-skin-inverted dark:text-skin-inverted-dark">
                Per page
            </div>
            <select class="leading-3 bg-gray-300 dark:bg-slate-800 dark:text-gray-300" id="take" wire:model="take" wire:change="updateLimit()">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="99999">All</option>
            </select>
            
        </div>

        <div>
            <div class="uppercase text-sm font-bold mb-1 text-skin-inverted dark:text-skin-inverted-dark">
                Sorting
            </div>
            <select class="leading-3 bg-gray-300 dark:bg-slate-800 dark:text-gray-300" id="order" wire:model="order" wire:change="updateOrder()">
                <option value="desc">Latest</option>
                <option value="asc">Oldest</option>
            </select>
            
        </div>
    </div>
    <div class="relative px-4 overflow-x-auto ">
        @if(count($lives) > 0)     
            <table class="w-full mx-auto">
                <thead class="p-2">
                    <tr class="p-2">
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Name</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Life ID</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Age</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Gender</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Location</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Died to</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Died</td>
                    </tr>
                </thead>
                <tbody class="p-2">
                    @forelse ($lives as $life)
                        <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                            <td class="p-2 border border-gray-400">
                                <div class="cursor-pointer hover:text-skin-base dark:hover:text-skin-base-dark" onclick="Livewire.dispatch('openModal', {component: 'modals.character.details', arguments: {character_id: {{$life->character_id}}}})">{{ $life->name->name ?? '-UNNAMED-' }}</div>
                            </td>
                            <td class="p-2 border border-gray-400">{{ $life->character_id }}</td>
                            <td class="p-2 border border-gray-400">{{ $life->age }}</td>
                            <td class="p-2 border border-gray-400">{{ $life->gender }}</td>
                            <td class="p-2 border border-gray-400">
                                <a target="_blank" title="View on Wondible's OneMap" href="https://onemap.wondible.com/#x={{ $life->pos_x }}&y={{ $life->pos_y }}&z=29&s=17&t={{ time() }}">
                                    {{ $life->pos_x  }} / {{ $life->pos_y  }}
                                </a>
                            </td>
                            <td class="p-2 border border-gray-400">{{ $life->died_to }}</td>
                            <td class="p-2 border border-gray-400">{{ date('Y-m-d H:i:s ', $life->timestamp) }}</td>
                        </tr>
                    @empty
                        
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $lives->links(data: ['scrollTo' => false]) }}
            </div>
        @else
            <div class="px-2 text-center italic dark:text-gray-400">No lives found for this player...</div>
        @endif
    </div>
</div>
