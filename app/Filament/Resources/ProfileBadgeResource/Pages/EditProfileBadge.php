<?php

namespace App\Filament\Resources\ProfileBadgeResource\Pages;

use App\Filament\Resources\ProfileBadgeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfileBadge extends EditRecord
{
    protected static string $resource = ProfileBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
