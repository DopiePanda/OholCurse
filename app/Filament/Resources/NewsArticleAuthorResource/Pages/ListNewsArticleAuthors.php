<?php

namespace App\Filament\Resources\NewsArticleAuthorResource\Pages;

use App\Filament\Resources\NewsArticleAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsArticleAuthors extends ListRecords
{
    protected static string $resource = NewsArticleAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
