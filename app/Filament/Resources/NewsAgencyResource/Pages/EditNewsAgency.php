<?php

namespace App\Filament\Resources\NewsAgencyResource\Pages;

use App\Filament\Resources\NewsAgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsAgency extends EditRecord
{
    protected static string $resource = NewsAgencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
