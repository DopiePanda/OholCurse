<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileRestrictionResource\Pages;
use App\Filament\Resources\ProfileRestrictionResource\RelationManagers;
use App\Models\ProfileRestriction;
use App\Models\Leaderboard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfileRestrictionResource extends Resource
{
    protected static ?string $model = ProfileRestriction::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Accounts';

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
                ToggleColumn::make('enabled')
                ->sortable(),
                TextColumn::make('player_name')
                ->sortable(),
                TextColumn::make('player_hash')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                ->form([
                    Toggle::make('enabled'),
                    TextInput::make('player_name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('player_hash')
                        ->required()
                        ->maxLength(255),
                ]),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        Toggle::make('enabled'),
                        TextInput::make('player_name')
                            ->required()
                            ->maxLength(255),
                        Select::make('player_hash')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Leaderboard::where('player_hash', 'like', "%{$search}%")->limit(15)->pluck('player_hash', 'id')->toArray())
                            ->getOptionLabelUsing(fn ($value): ?string => Leaderboard::find($value)?->player_hash),
                    ]),
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
        ];
    }
}
