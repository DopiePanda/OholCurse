<x-filament-panels::page>

<table>
    <thead>
        <tr>
            <th>Leadeboard</th>
            <th>Times killed</th>
            <th>Player hash</th>
            <th>Killer</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($deaths as $death)
            <tr>
                <td>{{ $death->leaderboard_name }}</td>
                <td>{{ $death->count }}</td>
                <td>
                    <a href="{{ route('player.interactions', ['hash' => $death->player_hash]) }}" target="_blank">
                        {{ $death->player_hash }}
                    </a>
                </td>
                <td>{{ $death->died_to }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</x-filament-panels::page>
