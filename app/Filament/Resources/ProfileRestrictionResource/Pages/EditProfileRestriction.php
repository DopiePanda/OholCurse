<?php

namespace App\Filament\Resources\ProfileRestrictionResource\Pages;

use App\Filament\Resources\ProfileRestrictionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfileRestriction extends EditRecord
{
    protected static string $resource = ProfileRestrictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
