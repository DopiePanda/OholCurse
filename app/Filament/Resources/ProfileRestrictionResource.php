<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileRestrictionResource\Pages;
use App\Filament\Resources\ProfileRestrictionResource\RelationManagers;
use App\Models\ProfileRestriction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfileRestrictionResource extends Resource
{
    protected static ?string $model = ProfileRestriction::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

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
            'index' => Pages\ListProfileRestrictions::route('/'),
            'create' => Pages\CreateProfileRestriction::route('/create'),
            'edit' => Pages\EditProfileRestriction::route('/{record}/edit'),
        ];
    }
}
