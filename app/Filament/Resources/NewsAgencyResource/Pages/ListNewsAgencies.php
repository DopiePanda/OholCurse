<?php

namespace App\Filament\Resources\NewsAgencyResource\Pages;

use App\Filament\Resources\NewsAgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsAgencies extends ListRecords
{
    protected static string $resource = NewsAgencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
