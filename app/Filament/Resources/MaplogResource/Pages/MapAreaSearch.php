<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use Filament\Resources\Pages\Page;

class MapAreaSearch extends Page
{
    protected static string $resource = MaplogResource::class;

    protected static string $view = 'filament.resources.maplog-resource.pages.map-area-search';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }
}
