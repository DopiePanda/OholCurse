<x-filament-panels::page>
    <table>
        @push('scripts')
            <script src="{{ asset('assets/js/fontawesome.js') }}"></script>
        @endpush
        <thead>
            <tr>
                <td>Date</td>
                <td>Log name</td>
                <td>Processed</td>
                <td>Processing time</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ date('Y-m-d H:i:s', explode('time_mapLog.txt', $log['file_name'])[0]) }}</td>
                    <td>{{ $log['file_name'] }}</td>
                    <td>
                        @if($log['found'])
                            <i class="text-green-500 fa-solid fa-square-check"></i>
                        @else
                            <i class="text-red-500 fa-solid fa-square-xmark"></i>
                        @endif
                    </td>
                    <td>{{ $log['time_elapsed'] ? $log['time_elapsed'].' seconds' : '-' }}</td>
                    <td>
                        @if(!$log['found'])
                            <button wire:click="download('{{ $log['file_name'] }}')">Download</button>
                        @endif
                        <div wire:loading></div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament-panels::page>
