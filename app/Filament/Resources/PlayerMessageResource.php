<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlayerMessageResource\Pages;
use App\Filament\Resources\PlayerMessageResource\RelationManagers;
use App\Models\PlayerMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlayerMessageResource extends Resource
{
    protected static ?string $model = PlayerMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('server_ip')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bot_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('timestamp')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('life_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('life_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('message')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pos_x')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pos_y')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('items')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('server_ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bot_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timestamp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('life_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('life_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pos_x')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pos_y')
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
            'index' => Pages\ListPlayerMessages::route('/'),
            'create' => Pages\CreatePlayerMessage::route('/create'),
            'edit' => Pages\EditPlayerMessage::route('/{record}/edit'),
        ];
    }
}
