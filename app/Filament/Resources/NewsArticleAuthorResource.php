<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleAuthorResource\Pages;
use App\Filament\Resources\NewsArticleAuthorResource\RelationManagers;
use App\Models\NewsArticleAuthor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;

class NewsArticleAuthorResource extends Resource
{
    protected static ?string $model = NewsArticleAuthor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'News';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('article_id')
                    ->required()
                    ->relationship('article', 'title'),
                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'username'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('article.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->sortable()
                    ->label('Author'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
            'index' => Pages\ListNewsArticleAuthors::route('/'),
            'create' => Pages\CreateNewsArticleAuthor::route('/create'),
            'edit' => Pages\EditNewsArticleAuthor::route('/{record}/edit'),
        ];
    }
}
