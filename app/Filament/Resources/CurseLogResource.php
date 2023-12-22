<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurseLogResource\Pages;
use App\Filament\Resources\CurseLogResource\RelationManagers;
use App\Models\CurseLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;

use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurseLogResource extends Resource
{
    protected static ?string $model = CurseLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

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
                TextColumn::make('type')
                ->extraAttributes(['class' => 'capitalize'])
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'curse' => 'danger',
                    'trust' => 'success',
                    'forgive' => 'warning',
                    'all' => 'gray',
                }),
                TextColumn::make('character_id')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('character_id', 'like', $search.'%');
                }),
                TextColumn::make('name.name')
                ->searchable(isIndividual: true, query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('name', 'like', $search.'%');
                })
                ->label('Character name'),
                TextColumn::make('leaderboard_recieved.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (CurseLog $record): string => route('player.curses', ['hash' => $record->player_hash]))
                ->openUrlInNewTab()
                ->label('Sender'),
                TextColumn::make('leaderboard.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (CurseLog $record): string => route('player.curses', ['hash' => $record->reciever_hash]))
                ->openUrlInNewTab()
                ->label('Reciever'),
            ])
            ->defaultSort('timestamp', 'desc')
            ->groups([

            ])
            ->filters([
                SelectFilter::make('type')
                ->options([
                    'curse' => 'Curse',
                    'forgive' => 'Forgive',
                    'trust' => 'Trust',
                    'all' => 'Forgive All',
                ]),
            ])
            ->headerActions([
                // ...
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
            'index' => Pages\ListCurseLogs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                    ->where('type', '!=', 'score');
    }
}
