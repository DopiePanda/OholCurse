<?php

namespace App\Filament\Resources\NewsAgenciesResource\Pages;

use App\Filament\Resources\NewsAgenciesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsAgencies extends EditRecord
{
    protected static string $resource = NewsAgenciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
