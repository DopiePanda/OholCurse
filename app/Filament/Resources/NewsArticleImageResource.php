<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleImageResource\Pages;
use App\Filament\Resources\NewsArticleImageResource\RelationManagers;
use App\Models\NewsArticleImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Auth;

use Filament\Forms\Components\Select;

class NewsArticleImageResource extends Resource
{
    protected static ?string $model = NewsArticleImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'News';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('article_id')
                    ->required()
                    ->relationship(
                        name: 'article', 
                        titleAttribute: 'title',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('id', 'desc'),
                        )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title} // Article ID: {$record->id}")
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255)
                    ->default('primary')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('caption')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->disk('public')
                    ->directory('assets/news-articles')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->default(Auth::user()->id)
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\TextColumn::make('article.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('caption')
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
            ->defaultSort('article_id', 'desc')
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
            'index' => Pages\ListNewsArticleImages::route('/'),
            'create' => Pages\CreateNewsArticleImage::route('/create'),
            'edit' => Pages\EditNewsArticleImage::route('/{record}/edit'),
        ];
    }
}
