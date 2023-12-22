<?php

namespace App\Filament\Resources\YumlogResource\Pages;

use App\Filament\Resources\YumlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditYumlog extends EditRecord
{
    protected static string $resource = YumlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
