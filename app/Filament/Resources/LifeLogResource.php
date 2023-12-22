<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LifeLogResource\Pages;
use App\Filament\Resources\LifeLogResource\RelationManagers;
use App\Models\LifeLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListLifeLogs::route('/'),
            'create' => Pages\CreateLifeLog::route('/create'),
            'edit' => Pages\EditLifeLog::route('/{record}/edit'),
        ];
    }
}
