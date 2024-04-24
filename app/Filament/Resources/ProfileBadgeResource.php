<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileBadgeResource\Pages;
use App\Filament\Resources\ProfileBadgeResource\RelationManagers;
use App\Models\ProfileBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfileBadgeResource extends Resource
{
    protected static ?string $model = ProfileBadge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('badge_id')
                    ->relationship('badge', 'name')
                    ->required(),
                Forms\Components\TextInput::make('player_hash')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('achieved_at')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('badge.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('player_hash')
                    ->searchable(),
                Tables\Columns\TextColumn::make('achieved_at')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfileBadges::route('/'),
            'create' => Pages\CreateProfileBadge::route('/create'),
            'edit' => Pages\EditProfileBadge::route('/{record}/edit'),
        ];
    }
}
