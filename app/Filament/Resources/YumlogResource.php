<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YumlogResource\Pages;
use App\Filament\Resources\YumlogResource\RelationManagers;

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
use Auth;
use Log;

use App\Models\Yumlog;
use App\Models\LifeLog;
use App\Models\CurseLog;
use App\Models\CurseLogTemp;


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

                File::lines($path)->each(function ($line) {
                    if(Str::contains($line, '| forgive |'))
                    {
                        try 
                        {
                            self::processLine($line);
                        } 
                        catch (\Throwable $th) 
                        {
                            Log::error($th);
                        }
                        
                    }
                });


                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::info("Yumlog processed in $time seconds");

            }
            catch(\Exception $e) 
            {
                Log::error('Exception returned when importing the yumlog');
                Log::error($e->getMessage());
            }
    }

    public static function processLine($line)
    {
        /*
            $parts[0] = timestamp | 1379020415 
            $parts[1] = type | forgive
            $parts[2] = character_id and name | 6641482 MELISA PLIMMER
            $parts[3] = curse_name | HIDE FORM
        */

        $parts = explode(' | ', $line);

        if(count($parts) == 4)
        {
            // Seperate character_id and character_name
            $character = explode(' ', $parts[2]);

            // Set character ID
            $character_id = $character[0];

            // Set default character name
            $character_name = null;

            // Check if character only has a first name
            if(count($character) == 2)
            {
                $character_name = $character[1];
            }

            // Check if character only has both first and last name
            if(count($character) == 3)
            {
                $character_name = $character[1].' '.$character[2];
            }

            // Create or update Yumlog report entry
            $report = Yumlog::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'character_id' => $character_id,
                ], 
                [
                    'timestamp' => $parts[0],
                    'character_name' => $character_name,
                    'curse_name' => $parts[3],
                    'type' => 'curse',
                    'status' => 5
                ]
            );

            // Check if life exists with submitted character ID
            $life = LifeLog::where('character_id', $character_id)
                    ->where('type', 'death')
                    ->first();

            if($life)
            {
                $offset = 3;

                // Check if curse log entry exists with submitted timestamp and life log player hash
                $curse = CurseLog::where('timestamp', '>=', ($parts[0] - $offset))
                        ->where('timestamp', '<=', ($parts[0] + $offset))
                        ->where('reciever_hash', $life->player_hash)
                        ->where('type', 'forgive')
                        ->first();

                if($curse)
                {
                    // If forgive entry is found in curse logs, set entry to hidden
                    $curse->hidden = 1;
                    $curse->save();
                }
                else
                {
                    // If forgive entry is not found, log a notice on it
                    Log::notice("Curse log entry not found for character: $character_id, ts: $parts[0], hash: $life->player_hash");
                }

                // Since life log entry is found, update Yumlog report with life information and set as verified
                try 
                {
                    self::setVerified($report, $life);
                } 
                catch (\Throwable $th) 
                {
                    Log::error($th);
                } 
            }
            else
            {
                // If life log entry is not found, create a temporary entry on it for re-verification at next log import
                CurseLogTemp::updateOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        'character_id' => $character_id,
                    ],
                    [
                        'timestamp' => $parts[0],
                    ]
                );
            }

        }
    }

    public static function setVerified($report, $life)
    {
        $report->player_hash = $life->player_hash;
        $report->gender = $life->gender;
        $report->age = $life->age;
        $report->died_to = $life->died_to;
        $report->pos_x = $life->pos_x ?? null;
        $report->pos_y = $life->pos_y ?? null;
        $report->verified = 1;
        $report->visible = 1;
        $report->save();
    }
}
