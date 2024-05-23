<div>
    <div class="grid grid-cols-3 gap-4">
        @foreach ($records as $record)
            <div class="p-2 rounded-md bg-gray-400">
                <div class="text-lg text-center">{{ $record->leaderboard_name }}</div>
                <div>Berries eaten:{{ $record->count }}</div>
            </div>
        @endforeach
    </div>
</div>