<?php

namespace App\Filament\Resources\UserContactResource\Pages;

use App\Filament\Resources\UserContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserContact extends EditRecord
{
    protected static string $resource = UserContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
