<div class="z-50 pt-6 pb-8 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark text-gray-400" style="z-index: 50 !important;">

    <div class="text-4xl text-center">
        <div><img class="mx-auto h-20" src="{{ asset($leaderboard->image) }}" /></div>
        <div>{{ $leaderboard->label }}</div> 
    </div>

    <div class="px-4 w-2/3 mx-auto" wire:ignore wire:key="{{ $leaderboard->id }}" x-data="{ chart: null }" x-init="
        const chart = new Chart(document.getElementById('myChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: $wire.labels,
            datasets: [{
            label: $wire.chart_name,
            data: $wire.dataset,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
            }]
        },
        options: {}
        });

        Livewire.on('updateChart', () => {
                chart.data.labels = $wire.labels;
                chart.data.datasets[0].label = $wire.chart_name;
                chart.data.datasets[0].data = $wire.dataset;

                chart.update();
            });
    ">
        <canvas wire:ignore id="myChart"></canvas>
    </div>
    
    <div class="py-4 text-center">
        <label wire:click="toggleGhost()" class="inline-flex items-center mx-auto cursor-pointer">
            <span class="me-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                <i class="fa-solid fa-user fa-2x @if(!$filter_ghosts) text-skin-base dark:text-skin-base-dark @endif" title="Non-ghost"></i>
            </span>
            <input wire:model="filter_ghosts" type="checkbox" class="sr-only peer" @if($filter_ghosts) checked @endif>
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                <i class="fa-solid fa-ghost fa-2x @if($filter_ghosts) text-skin-base dark:text-skin-base-dark @endif" title="Ghost"></i>
            </span>
          </label>
    </div>

    <div class="z-50 mt-6 relative overflow-x-auto mx-auto sm:px-6 lg:px-8">
        <table class="z-50 mx-auto mt-1 text-center border border-gray-400 shadow-lg">
            <thead>
                <tr class="border-b border-gray-400 text-white bg-skin-fill dark:bg-skin-fill-dark">
                    <td class="p-4 text-white border-r border-gray-400">#</td>
                    <td class="p-4 text-white border-r border-gray-400">Amount</td>
                    <td class="p-4 text-white border-r border-gray-400">Leaderboard</td>
                    <td class="p-4 text-white border-r border-gray-400">Character</td>
                    <td class="p-4 text-white border-r border-gray-400">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record) 
                    <tr class="even:bg-gray-300 odd:bg-white dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                        <td class="p-4 border border-gray-400">{{ $loop->index + 1 }}</td>
                        <td class="p-4 border border-gray-400">{{ $record->amount }}</td>
                        <td class="p-4 border border-gray-400">
                            @if(isset($record->character->player_hash) && isset($record->playerName->leaderboard_name)) 
                                <a class="font-semibold text-skin-base dark:text-skin-base-dark" href="{{ route('player.interactions', ['hash' => $record->character->player_hash]) }}">
                                    {{ $record->playerName->contact->nickname ?? $record->playerName->leaderboard_name }}
                                </a>
                            @else
                                <span title="Check again after 9AM tomorrow for updated data">
                                    - LEADERBOARD MISSING -
                                </span>
                            @endif
                        </td>
                        <td class="p-4 border border-gray-400">{{ $record->lifeName->name ?? 'UNNAMED' }}</td>
                        <td class="p-4 border border-gray-400">{{ date('Y-m-d H:i', $record->timestamp) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
