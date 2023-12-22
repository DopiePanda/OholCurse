<?php

namespace App\Filament\Resources\FoodLogResource\Pages;

use App\Filament\Resources\FoodLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFoodLog extends CreateRecord
{
    protected static string $resource = FoodLogResource::class;
}
