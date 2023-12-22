<?php

namespace App\Filament\Resources\LifeLogResource\Pages;

use App\Filament\Resources\LifeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLifeLog extends EditRecord
{
    protected static string $resource = LifeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
