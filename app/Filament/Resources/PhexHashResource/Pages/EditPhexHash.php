<?php

namespace App\Filament\Resources\PhexHashResource\Pages;

use App\Filament\Resources\PhexHashResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhexHash extends EditRecord
{
    protected static string $resource = PhexHashResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
