<?php

namespace App\Filament\Resources\LifeLogResource\Pages;

use App\Filament\Resources\LifeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListLifeLogs extends ListRecords
{
    protected static string $resource = LifeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '10') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
