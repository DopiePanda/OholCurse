<?php

namespace App\Livewire\Charts;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

use App\Models\LifeLog;

class LivesLivedChart extends ChartWidget
{
    protected static ?string $heading = 'Number of lives lived';

    protected static bool $isLazy = true;

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
            ],
        ],
    ];

    protected function getData(): array
    {
        $data = Trend::query(LifeLog::where('type', '=', 'birth'))
                    ->dateColumn('created_at')
                    ->between(
                        start: now()->createFromDate(null, 8, 20),
                        end: now(),
                    )
                    ->perDay()
                    ->count();
        //dd($data);
        return [
            'datasets' => [
                [
                    'label' => 'Births',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#888888',
                    'borderColor' => '#9BD0F5',
                    'drawActiveElementsOnTop' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
