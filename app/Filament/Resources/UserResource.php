<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Section as Card;
use App\Filament\Actions\ImpersonateLink;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\EditUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ViewUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ListUsers;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\CreateUser;

class UserResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'username';

    public function __construct()
    {
        static::$model = config('filament-authentication.models.User');
    }

    public static function getNavigationGroup(): ?string
    {
        return strval(__(config('filament-authentication.section.group') ?? 'filament-authentication::filament-authentication.section.group'));
    }

    public static function getLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.user'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.users'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('username')
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.name')))
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                        TextInput::make('password')
                            ->same('passwordConfirmation')
                            ->password()
                            ->maxLength(255)
                            ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                            ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : '')
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.password'))),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrated(false)
                            ->maxLength(255)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.confirm_password'))),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(config('filament-authentication.preload_roles'))
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.id'))),
                TextColumn::make('username')
                ->searchable()
                ->sortable()
                ->label(strval(__('filament-authentication::filament-authentication.field.user.name'))),
                TextColumn::make('player_hash')
                ->searchable()
                ->sortable()
                ->label(strval(__('Hash'))),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.email')))
                    ->visible(fn (): bool => auth()->user()->can('view users email')),
                TextColumn::make('roles.name')->badge()
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.created_at'))),
            ])
            ->filters([

            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ImpersonateLink::make()
                ->visible(fn (): bool => auth()->user()->can('impersonate users')),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
            'view'   => ViewUser::route('/{record}'),
        ];
    }
}
