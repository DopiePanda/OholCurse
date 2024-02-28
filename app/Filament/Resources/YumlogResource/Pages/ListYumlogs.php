<?php

namespace App\Filament\Resources\YumlogResource\Pages;

use App\Filament\Resources\YumlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListYumlogs extends ListRecords
{
    protected static string $resource = YumlogResource::class;

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '10') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
