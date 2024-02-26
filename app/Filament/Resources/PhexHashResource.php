<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhexHashResource\Pages;
use App\Filament\Resources\PhexHashResource\RelationManagers;
use App\Models\PhexHash;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PhexHashResource extends Resource
{
    protected static ?string $model = PhexHash::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('player_hash')
                    ->maxLength(255),
                Forms\Components\TextInput::make('character_id')
                    ->numeric(),
                Forms\Components\TextInput::make('olgc_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('olgc_hash')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('olgc_hash_full')
                    ->maxLength(255),
                Forms\Components\TextInput::make('px_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('px_hash')
                    ->maxLength(255),
                Forms\Components\TextInput::make('px_hash_full')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phex.nickname')
                    ->searchable(['nickname'], isIndividual: true)
                    ->default('No nickname'),
                Tables\Columns\TextColumn::make('player.leaderboard_name')
                    ->searchable(['leaderboard_name'], isIndividual: true)
                    ->url(fn (PhexHash $record): string => route('player.curses', ['hash' => $record->player_hash ?? 'missing']))
                    ->openUrlInNewTab()
                    ->placeholder('N/A')
                    ->label('Leaderboard name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('olgc_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('olgc_hash')
                    ->searchable(),
                Tables\Columns\TextColumn::make('olgc_hash_full')
                    ->searchable(),
                Tables\Columns\TextColumn::make('px_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('px_hash')
                    ->searchable(),
                Tables\Columns\TextColumn::make('px_hash_full')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('player.leaderboard_name', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                    ->with('phex');
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
            'index' => Pages\ListPhexHashes::route('/'),
            'create' => Pages\CreatePhexHash::route('/create'),
            'edit' => Pages\EditPhexHash::route('/{record}/edit'),
        ];
    }
}
