<?php

namespace App\Livewire\Charts;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

use App\Models\LifeLog;

class FamilyOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $data = Trend::query(LifeLog::where('type', '=', 'birth'))
                    ->dateColumn('created_at')
                    ->between(
                        start: now()->createFromDate(null, 8, 20),
                        end: now(),
                    )
                    ->perMonth()
                    ->sum('family_type');

        return [
            Stat::make('Unique views', '192.1k')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($data->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),
            // ...
        ];
    }
}
