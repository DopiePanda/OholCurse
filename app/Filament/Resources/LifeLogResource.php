<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LifeLogResource\Pages;
use App\Filament\Resources\LifeLogResource\RelationManagers;
use App\Models\LifeLog;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LifeLogResource extends Resource
{
    protected static ?string $model = LifeLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('timestamp')
                ->sortable()
                ->dateTime(),
                TextColumn::make('type')
                ->extraAttributes(['class' => 'capitalize'])
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'birth' => 'success',
                    'death' => 'gray',
                }),
                TextColumn::make('gender')
                ->extraAttributes(['class' => 'capitalize'])
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'female' => 'success',
                    'male' => 'info',
                }),
                TextColumn::make('leaderboard.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (LifeLog $record): string => route('player.curses', ['hash' => $record->player_hash]))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Leaderboard name'),
                
                
                TextColumn::make('family_type')
                ->hidden(fn ($livewire) => $livewire->getTableFilterState('type')['value'] === 'death')
                ->sortable(),
                TextColumn::make('name.name')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('name', 'like', $search.'%');
                })
                ->placeholder('Unnamed')
                ->label('Character name'),
                TextColumn::make('character_id')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('character_id', 'like', $search.'%');
                }),
                TextColumn::make('age')
                ->hidden(fn ($livewire) => $livewire->getTableFilterState('type')['value'] === 'birth'),
                TextColumn::make('died_to')
                ->hidden(fn ($livewire) => $livewire->getTableFilterState('type')['value'] === 'birth')
                ->sortable(),
                TextColumn::make('pos_x')
                ->sortable(),
                TextColumn::make('pos_y')
                ->sortable(),
                
                
            ])
            ->defaultSort('timestamp', 'desc')
            ->defaultPaginationPageOption(50)
            ->filters([
                SelectFilter::make('type')
                ->options([
                    'birth' => 'Birth',
                    'death' => 'Death',
                ])
                ->default('birth'),
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
            'index' => Pages\ListLifeLogs::route('/'),
        ];
    }
}
