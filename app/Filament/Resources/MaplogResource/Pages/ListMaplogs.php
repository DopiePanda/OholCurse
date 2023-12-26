<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListMaplogs extends ListRecords
{
    protected static string $resource = MaplogResource::class;

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
