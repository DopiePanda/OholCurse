<?php

namespace App\Filament\Resources\NewsArticleImageResource\Pages;

use App\Filament\Resources\NewsArticleImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsArticleImages extends ListRecords
{
    protected static string $resource = NewsArticleImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
