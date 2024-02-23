<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListMaplogs extends ListRecords
{
    protected static string $resource = MaplogResource::class;

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '10') ? $query->count() : $this->getTableRecordsPerPage());
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                    ->where('character_id', '!=', -1)
                    ->where('object_id', '!=', 0)
                    ->whereHas('name')
                    ->skip(0)
                    ->take(1000);
    }
}
