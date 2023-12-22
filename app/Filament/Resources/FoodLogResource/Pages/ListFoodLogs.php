<?php

namespace App\Filament\Resources\FoodLogResource\Pages;

use App\Filament\Resources\FoodLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodLogs extends ListRecords
{
    protected static string $resource = FoodLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
