<?php

namespace App\Filament\Resources\FoodLogResource\Pages;

use App\Filament\Resources\FoodLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodLog extends EditRecord
{
    protected static string $resource = FoodLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
