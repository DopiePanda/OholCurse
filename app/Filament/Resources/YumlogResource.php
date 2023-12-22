<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YumlogResource\Pages;
use App\Filament\Resources\YumlogResource\RelationManagers;
use App\Models\Yumlog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Livewire;
use App\Livewire\Modals\UploadLogfile;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class YumlogResource extends Resource
{
    protected static ?string $model = Yumlog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Livewire::make(UploadLogfile::class)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('timestamp')
                ->sortable()
                ->dateTime('Y-m-d H:i'),
                TextColumn::make('user.username')
                ->searchable(['username'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.curses', ['hash' => $record->user->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Submitted by'),
                TextColumn::make('leaderboard.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.curses', ['hash' => $record->leaderboard->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Leaderboard name'),
                TextColumn::make('curse_name')
                ->searchable(['curse_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.reports', ['hash' => $record->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Curse name'),
                TextColumn::make('character_name')
                ->searchable(['character_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.lives', ['hash' => $record->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Character name'),
                TextColumn::make('character_id')
                ->searchable(['character_id'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.lives', ['hash' => $record->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Character ID')
                ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('verified'),
                SelectColumn::make('status')
                ->options([
                    '0' => 'Unverified',
                    '1' => 'Verified',
                    '2' => 'Archived',
                    '3' => 'Curse-check',
                    '4' => 'Forgiven',
                    '5' => 'Imported',
                ])
            ])
            ->defaultSort('timestamp', 'desc')
            ->defaultPaginationPageOption(50)
            ->filters([
                //
            ])
            ->actions([
                
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('edit')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->edit()),
                    BulkAction::make('forceDelete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
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
            'index' => Pages\ListYumlogs::route('/'),
        ];
    }
}
