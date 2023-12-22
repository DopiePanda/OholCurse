<?php

namespace App\Livewire\Charts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;
use DB;

use App\Models\LifeLog;

class FamilyDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
            ],
        ],
    ];

    protected function getData(): array
    {
        $query = LifeLog::where('type', '=', 'birth')
                        ->whereBetween('created_at', ['2023-08-20', now()])
                        ->select(DB::raw('COUNT(family_type) as count'), 'family_type')
                        ->groupBy('family_type')
                        ->get()
                        ->toArray();
        
        //dd($query);
        $data = Arr::pluck($query, 'count');
        $labels = Arr::pluck($query, 'family_type');
        $labels = Arr::map($labels, function (string $value, string $key) {
            return ucfirst($value);
        });
        //dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Births',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(125, 60, 86)'
                      ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
