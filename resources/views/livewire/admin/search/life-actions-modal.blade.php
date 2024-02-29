<div class="w-full">
    <div class="p-8">
        <form wire:submit="search">
            <div class="p-2 mt-4">
                <label class="block text-skin-base dark:text-skin-base-dark">Enter character ID</label>
                <input class="mt-2 w-full" wire:model="character_id" type="text" placeholder="7540234" />
            </div>
            <div class="p-2 mt-4">
                <button class="py-2 px-4 w-full bg-skin-fill dark:bg-skin-fill-dark text-white" type="submti">Search</button>
            </div>
            <div class="mt-12 text-primary-500" wire:loading>
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                <div>Searching for player</div>
                <div class="text-gray-400 text-sm">This can take a minute or two.</div>
            </div>
        </form>
    </div>
    
    @if($coordinates)
        <div class="p-8 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <table class="text-gray-800 dark:text-gray-200">
                <thead>
                    <tr>
                        <th class="p-4">Date</th>
                        <th class="p-4">Pos X</th>
                        <th class="p-4">Pos Y</th>
                        <th class="p-4">Object</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coordinates as $log)
                        <tr class="p-2">
                            <td class="p-4">{{ date('Y-m-d H:i', $log->timestamp) }}</td>
                            <td class="p-4">{{ $log->pos_x }}</td>
                            <td class="p-4">{{ $log->pos_y }}</td>
                            <td class="p-4">
                                @if($log->object)
                                    {{ explode('#', $log->object->name)[0] ?? 'missing' }}
                                @else
                                    {{ $log->object_id }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    @endif
</div>
