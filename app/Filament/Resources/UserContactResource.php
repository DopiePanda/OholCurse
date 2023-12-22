<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserContactResource\Pages;
use App\Filament\Resources\UserContactResource\RelationManagers;
use App\Models\UserContact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserContactResource extends Resource
{
    protected static ?string $model = UserContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

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
                TextColumn::make('type')
                ->extraAttributes(['class' => 'capitalize'])
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'friend' => 'success',
                    'dubious' => 'warning',
                    'enemy' => 'danger',
                }),
                TextColumn::make('user.username')
                ->searchable(['username'], isIndividual: true)
                ->url(fn (UserContact $record): string => route('player.curses', ['hash' => $record->user->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('User'),
                TextColumn::make('player.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (UserContact $record): string => route('player.curses', ['hash' => $record->player->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Contact'),
                TextColumn::make('nickname')
                ->searchable(['nickname'], isIndividual: true)
                ->placeholder('N/A')
                ->label('Nickname'),
                TextColumn::make('phex_hash')
                ->searchable(['phex_hash'], isIndividual: true)
                ->placeholder('N/A')
                ->label('Phex Hash'),
                TextColumn::make('created_at')
                ->sortable()
                ->dateTime(),
                TextColumn::make('updated_at')
                ->sortable()
                ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->groups([
                'type',
                'user.username',
            ])
            ->filters([
                SelectFilter::make('type')
                ->options([
                    'friend' => 'Friend',
                    'dobious' => 'Dobious',
                    'enemy' => 'Enemy',
                ])
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
            'index' => Pages\ListUserContacts::route('/'),
        ];
    }
}
