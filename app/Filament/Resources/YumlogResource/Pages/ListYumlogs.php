<?php

namespace App\Filament\Resources\YumlogResource\Pages;

use App\Filament\Resources\YumlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYumlogs extends ListRecords
{
    protected static string $resource = YumlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
