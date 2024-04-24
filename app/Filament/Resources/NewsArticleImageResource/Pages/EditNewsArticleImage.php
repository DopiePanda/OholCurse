<?php

namespace App\Filament\Resources\NewsArticleImageResource\Pages;

use App\Filament\Resources\NewsArticleImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsArticleImage extends EditRecord
{
    protected static string $resource = NewsArticleImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
