<div class="px-4 mt-8">
    @foreach ($curses as $curse)
        <div class="my-1 p-2 flex flex-row border border-gray-400">
            <div>{{ $curse->leaderboard_recieved->leaderboard_name ?? $curse->player_hash }}</div>
            <div class="px-2"> => </div>
            <div class="grow">{{ $curse->leaderboard->leaderboard_name ?? $curse->reciever_hash }}</div>
            <div class="text-right">{{ date("y-m-d H:i:s", $curse->timestamp) }}</div>
            <div class="px-2">
                <i wire:click="toggle({{ $curse->id }} )"
                class="fa-solid fa-eye @if($curse->hidden == true) text-red-500 @else text-green-500 @endif">

                </i>
            </div>
        </div>
    @endforeach
</div>
