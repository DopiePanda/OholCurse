<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderboardResource\Pages;
use App\Filament\Resources\LeaderboardResource\RelationManagers;

use App\Models\GameObject;
use App\Models\GameLeaderboard;

use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaderboardResource extends Resource
{
    protected static ?string $model = GameLeaderboard::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                ->options([
                    'weekly' => 'Weekly',
                    'monthly' => 'Monthly',
                ])
                ->default('weekly')
                ->selectablePlaceholder(false),
                FileUpload::make('image'),
                TextInput::make('label'),
                TextInput::make('page_title'),
                Toggle::make('multi')
                ->live(),
                Select::make('object_id')
                ->options(GameObject::all()->pluck('name', 'id'))
                ->hidden(fn (Get $get): bool => $get('multi'))
                ->required(fn (Get $get): bool => ! $get('multi'))
                ->searchable(),
                Select::make('multi_objects')
                ->multiple()
                ->options(GameObject::all()->pluck('name', 'id'))
                ->hidden(fn (Get $get): bool => ! $get('multi'))
                ->required(fn (Get $get): bool => $get('multi'))
                ->searchable(),
                Toggle::make('enabled'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ToggleColumn::make('enabled'),

                Tables\Columns\ImageColumn::make('image')
                ->disk('filament')
                ->square(),

                Tables\Columns\TextColumn::make('label'),

                Tables\Columns\TextColumn::make('object_id'),

                Tables\Columns\IconColumn::make('multi')
                ->boolean(),
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
            'index' => Pages\ListLeaderboards::route('/'),
            'create' => Pages\CreateLeaderboard::route('/create'),
            'edit' => Pages\EditLeaderboard::route('/{record}/edit'),
        ];
    }
}
