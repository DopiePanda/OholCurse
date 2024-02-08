<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaplogResource\Pages;
use App\Filament\Resources\MaplogResource\RelationManagers;

use App\Models\MapLog;
use App\Models\LifeLog;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;

use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;

use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Carbon\Carbon;

class MaplogResource extends Resource
{
    protected static ?string $model = MapLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Map Logs';
    protected static ?string $navigationGroup = 'Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                ->rowIndex(),
                TextColumn::make('timestamp')
                ->sortable()
                ->dateTime(),
                TextColumn::make('character_id')
                ->searchable(isIndividual: true),
                TextColumn::make('name.name')
                ->searchable(['name'], isIndividual: true),
                TextColumn::make('object.name')
                ->searchable(isIndividual: true),
                TextColumn::make('object_id')
                ->searchable(isIndividual: true),
                TextColumn::make('pos_x')
                ->searchable(isIndividual: true),
                TextColumn::make('pos_y')
                ->searchable(isIndividual: true),
            ])
            ->actions([
                
            ])
            ->headerActions([
                Action::make('Area Search')
                ->url(fn (): string => ('./maplogs/area')),
            ])
            ->bulkActions([
                
            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(10);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaplogs::route('/'),
            'area' => Pages\AreaSearch::route('/area'),
            'overview' => Pages\MapLogOverview::route('/overview'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                    ->where('character_id', '!=', -1)
                    ->where('object_id', '!=', 0)
                    ->whereHas('name');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\AreaSearch::class,
            Pages\MapLogOverview::class,
        ]);
    }
}
