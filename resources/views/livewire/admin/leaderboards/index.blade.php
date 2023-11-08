<div>
    <div class="text-2xl text-center my-4">Manage Leaderboards</div>
    <button class="px-6 py-2 bg-green-500 rounded-md" onclick="Livewire.dispatch('openModal', { component: 'admin.leaderboards.create' })">
        <i class="mr-1 fas fa-plus-square"></i>
        <span class="uppercase">New Leaderboard</span>
    </button>
    <table class="overflow-x-scroll mt-3 w-full text-center border border-gray-400 shadow-lg overflow-x-scroll">
        <thead>
            <tr class="border-b border-gray-400">
                <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Image</td>
                <td class="p-4 bg-blue-400 text-white border-r border-blue-500">Label</td>
                <td colspan=2 class="p-4 bg-blue-400 text-white border-r border-blue-500">Actions</td>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaderboards as $leaderboard)
                <tr class="bg-white">
                    <td class="p-4 border border-gray-400 text-sm italic">
                        <img class="h-12 mx-auto" src="{{ asset($leaderboard->image) }}" title="{{ $leaderboard->object->name }}" />
                        @if($leaderboard->multi)
                            <div>
                                @foreach(json_decode($leaderboard->multi_objects) as $object)
                                    <span>@if(!$loop->last) {{ $object.',' }}  @else {{ $object }}  @endif</span>
                                @endforeach
                            </div>
                        @else
                            <div>{{ $leaderboard->object_id }}</div>
                        @endif
                    </td>
                    <td class="p-4 border border-gray-400">
                        <div class="font-semibold">{{ $leaderboard->label }}</div>
                        <div class="text-sm italic">{{ $leaderboard->page_title }}</div>
                    </td>
                    <td class="p-4 border border-gray-400">
                        <a wire:click="$dispatch('openModal', { component: 'admin.leaderboards.edit', arguments: {{ json_encode(['id' => $leaderboard->id]) }} })">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td class="p-4 border border-gray-400">
                        <a wire:click="toggleLeaderboard({{ $leaderboard->id }})">
                            @if( $leaderboard->enabled )
                                <i class="text-green-600 fas fa-eye"></i>
                            @else
                                <i class="text-red-600 fas fa-eye-slash"></i>
                            @endif
                        </a>
                    </td>   
                </tr>    
            @empty
                
            @endforelse
        </tbody>
    </table>
</div>
