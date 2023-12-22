<?php

namespace App\Filament\Resources\CurseLogResource\Pages;

use App\Filament\Resources\CurseLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurseLogs extends ListRecords
{
    protected static string $resource = CurseLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
