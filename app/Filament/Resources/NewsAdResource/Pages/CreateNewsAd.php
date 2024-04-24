<?php

namespace App\Filament\Resources\NewsAdResource\Pages;

use App\Filament\Resources\NewsAdResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsAd extends CreateRecord
{
    protected static string $resource = NewsAdResource::class;
}
