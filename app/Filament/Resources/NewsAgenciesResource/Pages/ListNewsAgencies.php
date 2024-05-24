<?php

namespace App\Filament\Resources\NewsAgenciesResource\Pages;

use App\Filament\Resources\NewsAgenciesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsAgencies extends ListRecords
{
    protected static string $resource = NewsAgenciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
