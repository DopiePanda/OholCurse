<?php

namespace App\Filament\Resources\CurseLogResource\Pages;

use App\Filament\Resources\CurseLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;


class ListCurseLogs extends ListRecords
{
    protected static string $resource = CurseLogResource::class;

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '25') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
