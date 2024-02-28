<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodLogResource\Pages;
use App\Filament\Resources\FoodLogResource\RelationManagers;
use App\Models\FoodLog;
use App\Models\LifeLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodLogResource extends Resource
{
    protected static ?string $model = FoodLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

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
                TextColumn::make('timestamp')
                ->sortable()
                ->dateTime(),
                TextColumn::make('birth.leaderboard.leaderboard_name')
                ->searchable(isIndividual: true)
                ->url(fn (FoodLog $record): string => route('player.curses', ['hash' => $record->birth->player_hash ?? 'none']))
                ->openUrlInNewTab()
                ->placeholder('N/A'),
                TextColumn::make('character_id')
                ->searchable(['character_id'], isIndividual: true)
                ->url(fn (FoodLog $record): string => route('player.curses', ['hash' => $record->birth->player_hash ?? 'none']))
                ->openUrlInNewTab(),
                TextColumn::make('birth.name.name')
                ->searchable(isIndividual: true)
                ->url(fn (FoodLog $record): string => route('player.lives', ['hash' => $record->birth->player_hash ?? 'none']))
                ->openUrlInNewTab()
                ->placeholder('Unnamed')
                ->label('Character name'),
                TextColumn::make('object.name')
                ->searchable(['name'], isIndividual: true)
                ->label('Object'),
                TextColumn::make('object_id'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes())
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(10)
            ->filters([
                SelectFilter::make('objectId')
                ->relationship('object', 'id')
                ->searchable()
                ->preload(),
                SelectFilter::make('objectName')
                ->relationship('object', 'name')
                ->searchable()
                ->preload()
            ])
            ->groups([

            ])
            ->actions([
             
            ])
            ->bulkActions([
                
            ]);
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
            'index' => Pages\ListFoodLogs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                ->skip(0)
                ->take(1000);
    }
}
