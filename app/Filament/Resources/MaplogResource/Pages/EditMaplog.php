<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaplog extends EditRecord
{
    protected static string $resource = MaplogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
