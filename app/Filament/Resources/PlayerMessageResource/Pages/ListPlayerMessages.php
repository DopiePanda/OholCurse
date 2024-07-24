<?php

namespace App\Filament\Resources\PlayerMessageResource\Pages;

use App\Filament\Resources\PlayerMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlayerMessages extends ListRecords
{
    protected static string $resource = PlayerMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
