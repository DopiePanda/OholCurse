<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YumlogResource\Pages;
use App\Filament\Resources\YumlogResource\RelationManagers;
use App\Models\Yumlog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Livewire;

use App\Livewire\Modals\UploadLogfile;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;


class YumlogResource extends Resource
{
    protected static ?string $model = Yumlog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $navigationGroup = 'Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
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
                TextColumn::make('status')
            ])
            ->defaultSort('timestamp', 'desc')
            ->defaultPaginationPageOption(50)
            ->groups([
                Group::make('curse_name')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('curse_name')),
            ])
            ->filters([
                //
            ])
            ->actions([
                
            ])
            ->headerActions([
                Action::make('import')
                ->form([
                    FileUpload::make('yumlog')
                    ->required()
                    ->acceptedFileTypes(['text/plain'])
                    ->preserveFilenames()
                    ->storeFiles(false)
                ])
                ->label('Import Custom YumLog')
                ->action(function (array $data, array $arguments): void {
                    $path = $data['yumlog']->getRealPath();
                    self::processFile($path);
                })
                
            ])
            ->bulkActions([

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                    ->select('*')
                    ->groupBy('curse_name');
    }

    public static function processFile($path)
    {
        $start_time = microtime(true);

        try{
                ini_set('max_execution_time', 300);
                //DB::beginTransaction();

                File::lines($path)->each(function ($line) {
                    if(Str::contains($line, '| forgive |'))
                    {
                        try {
                            self::processLine($line);
                        } catch (\Throwable $th) {
                            Log::error($th);
                        }
                        
                    }
                });

                // Commit the DB transaction
                //DB::commit();

                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::info("Yumlog processed in $time seconds");

            }catch(\Exception $e) {
            
                // Rollback DB transaction
                //DB::rollback();

                // Log exception message
                Log::error('Exception returned when importing the yumlog');
                Log::error($e->getMessage());
            }
    }

    public static function processLine($line)
    {
        Log::debug($line);
    }
}
