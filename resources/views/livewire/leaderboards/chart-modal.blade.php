<div class="py-6 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark text-gray-400">

    <div class="text-4xl text-center">
        <div><img class="mx-auto h-20" src="{{ asset($leaderboard->image) }}" /></div>
        <div>{{ $leaderboard->label }}</div> 
    </div>

    <div class="my-2 w-full lg:w-2/3 mx-auto">
        <canvas id="recordChart"></canvas>
    </div>
    
    <div class="mt-6 relative overflow-x-auto mx-auto sm:px-6 lg:px-8">
        <table class="mx-auto mt-1 text-center border border-gray-400 shadow-lg">
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
                        <td class="p-4 border border-gray-400">{{ $record->lifeName->name }}</td>
                        <td class="p-4 border border-gray-400">{{ date('Y-m-d H:i', $record->timestamp) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-data x-init="

        const ctx = document.getElementById('recordChart');
        const data=$wire.chart_data;
        console.log(data);
    
        const labels=data.map(item=>item.timestamp);
        const values=data.map(item=>item.amount);
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: 'Record amount',
              data: values,
              borderWidth: 2
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
    ">
    </div>
</div>
