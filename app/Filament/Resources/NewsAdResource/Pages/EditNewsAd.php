<?php

namespace App\Filament\Resources\NewsAdResource\Pages;

use App\Filament\Resources\NewsAdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsAd extends EditRecord
{
    protected static string $resource = NewsAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
