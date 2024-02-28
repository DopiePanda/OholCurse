<?php

namespace App\Filament\Resources\FoodLogResource\Pages;

use App\Filament\Resources\FoodLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListFoodLogs extends ListRecords
{
    protected static string $resource = FoodLogResource::class;

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '10') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
