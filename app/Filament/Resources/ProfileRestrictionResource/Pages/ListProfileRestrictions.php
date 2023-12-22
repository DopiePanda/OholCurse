<?php

namespace App\Filament\Resources\ProfileRestrictionResource\Pages;

use App\Filament\Resources\ProfileRestrictionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfileRestrictions extends ListRecords
{
    protected static string $resource = ProfileRestrictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
