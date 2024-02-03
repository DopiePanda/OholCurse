<x-filament-panels::page>
    <table>
        @push('scripts')
            <script src="{{ asset('assets/js/fontawesome.js') }}"></script>
        @endpush
        <thead>
            <tr class="p-2">
                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark border border-skin-base dark:border-skin-base-dark">Date</td>
                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark border border-skin-base dark:border-skin-base-dark">Log name</td>
                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark border border-skin-base dark:border-skin-base-dark">Processed</td>
                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark border border-skin-base dark:border-skin-base-dark">Processing time</td>
                <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark border border-skin-base dark:border-skin-base-dark">Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr class="p-2">
                    <td class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-white dark:border-gray-400">{{ date('Y-m-d H:i:s', intval(explode('time_mapLog.txt', $log['file_name'])[0])) }}</td>
                    <td class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-white dark:border-gray-400">{{ $log['file_name'] }}</td>
                    <td class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-white dark:border-gray-400">
                        @if($log['found'])
                            <i class="text-green-500 fa-solid fa-square-check"></i>
                        @else
                            <i class="text-red-500 fa-solid fa-square-xmark"></i>
                        @endif
                    </td>
                    <td class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-white dark:border-gray-400">{{ $log['time_elapsed'] ? $log['time_elapsed'].' seconds' : '-' }}</td>
                    <td class="p-2 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-white dark:border-gray-400"> 
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
