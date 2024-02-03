<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use App\Filament\Traits\HasParentResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class AreaSearch extends ListRecords
{
    use InteractsWithForms;

    protected static string $resource = MaplogResource::class;

    protected static string $view = 'filament.pages.map-area-search';

    protected static ?string $navigationLabel = 'Map Area Search';
    protected static ?string $navigationIcon = 'heroicon-s-book-open';
    protected static ?string $navigationGroup = 'Logs';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('slug'),
                RichEditor::make('content'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                ->rowIndex(),
                TextColumn::make('timestamp')
                ->sortable()
                ->dateTime(),
                TextColumn::make('character_id')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('character_id', 'like', $search.'%');
                }),
                TextColumn::make('name.name')
                ->searchable(['name'], isIndividual: true),
                TextColumn::make('object_id')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('object_id', 'like', $search.'%');
                }),
                TextColumn::make('pos_x')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('pos_x', 'like', $search.'%');
                }),
                TextColumn::make('pos_y')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('pos_y', 'like', $search.'%');
                }),
            ])
            ->groups([
                'character_id',
                'object_id',
            ])
            ->filters([

            ])
            ->actions([

            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(10);
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === '10') ? $query->count() : $this->getTableRecordsPerPage());
    }

    protected function getTableQuery(): Builder 
    {
        return parent::getTableQuery()->with('name');
    }

    public function getSubNavigation(): array
    {
        return static::getResource()::getRecordSubNavigation($this);
    }
}
