<?php

namespace App\Filament\Resources\LifeLogResource\Pages;

use App\Filament\Resources\LifeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLifeLogs extends ListRecords
{
    protected static string $resource = LifeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
