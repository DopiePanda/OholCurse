<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YumlogResource\Pages;
use App\Filament\Resources\YumlogResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;

use App\Livewire\Modals\UploadLogfile;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
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
use App\Models\PhexHash;


class YumlogResource extends Resource
{
    protected static ?string $model = Yumlog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $navigationGroup = 'Accounts';


    protected static ?bool $hideForgives = true;

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
                TextColumn::make('user.username')
                ->searchable(['username'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.curses', ['hash' => $record->user->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Submitted by')
                ->sortable()
                ->visible(fn (): bool => auth()->user()->can('view yumlog uploader')),
                TextColumn::make('leaderboard.leaderboard_name')
                ->searchable(['leaderboard_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.curses', ['hash' => $record->leaderboard->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Leaderboard name')
                ->sortable(),
                TextColumn::make('curse_name')
                ->searchable(['curse_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.reports', ['hash' => $record->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Curse name')
                ->sortable(),
                TextColumn::make('character_name')
                ->searchable(['character_name'], isIndividual: true)
                ->url(fn (Yumlog $record): string => route('player.lives', ['hash' => $record->player_hash ?? 'missing']))
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Character name')
                ->sortable(),
                TextColumn::make('character_id')
                ->searchable(['character_id'], isIndividual: true)
                ->url(fn (Yumlog $record): string => $record->player_hash ? route('player.lives', ['hash' => $record->player_hash ?? 'missing']) : '')
                ->openUrlInNewTab()
                ->placeholder('N/A')
                ->label('Character ID')
                ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('verified')
                ->icon(fn (string $state): string => match ($state) {
                    '0' => 'heroicon-o-clock',
                    '1' => 'heroicon-o-check-circle',
                })
                ->color(fn (string $state): string => match ($state) {
                    '0' => 'warning',
                    '1' => 'success',
                    default => 'gray',
                }),
                ToggleColumn::make('visible')
                ->visible(fn (): bool => auth()->user()->can('edit yumlog')),
            ])
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(50)
            ->groups([
                Group::make('curse_name')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('curse_name')),
                Group::make('leaderboard.leaderboard_name')
                ->groupQueryUsing(fn (Builder $query) => $query->groupBy('leaderboard_name')),
            ])
            ->filters([
                SelectFilter::make('leaderboard_name')
                ->relationship('leaderboard', 'leaderboard_name')
                ->searchable(),
                SelectFilter::make('status')
                ->options([
                    '0' => 'Unverified',
                    '1' => 'Verified',
                    '2' => 'Archived',
                    '3' => 'Curse-check',
                    '4' => 'Forgiven later',
                    '5' => 'Custom',
                ])
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
                    ->storeFiles(false),
                    Checkbox::make('hide_forgives')
                    ->label('Hide the forgives sent from public view')
                    ->default(true)
                    ->inline(true),

                ])
                ->label('Import Custom YumLog')
                ->action(function (array $data, array $arguments): void {
                    $path = $data['yumlog']->getRealPath();
                    $hide = $data['hide_forgives'];
                    self::processFile($path, $hide);
                })
                
            ])
            ->bulkActions([
                BulkAction::make('delete')
                ->requiresConfirmation()
                ->action(fn (Collection $records) => $records->each->delete())
                ->visible(fn (): bool => auth()->user()->can('delete bulk yumlog')),
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
        $status = [0,1,2,3,4];

        if(auth()->user()->can('view all yumlogs'))
        {
            $status = [0,1,2,3,4,5];
        }
        
        return parent::getEloquentQuery()
                ->with('leaderboard')
                ->whereIn('status', $status);
    }

    public static function processFile($path, $hide)
    {
        $start_time = microtime(true);

        try{
                ini_set('max_execution_time', 300);

                File::lines($path)->each(function ($line) use ($hide) {
                    if(Str::contains($line, '| forgive |'))
                    {
                        try 
                        {
                            self::processLine($line, $hide);
                        } 
                        catch (\Throwable $th) 
                        {
                            Log::channel('yumlog')->error($th);
                        }
                    }
                    if(Str::contains($line, '| phex_list |'))
                    {
                        try 
                        {
                            self::processPhexHash($line);
                        } 
                        catch (\Throwable $th) 
                        {
                            Log::channel('yumlog')->error($th);
                        }
                    }
                });


                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::channel('yumlog')->info("Yumlog processed in $time seconds");

            }
            catch(\Exception $e) 
            {
                Log::channel('yumlog')->error('Exception returned when importing the yumlog');
                Log::channel('yumlog')->error($e->getMessage());
            }
    }

    public static function processLine($line, $hide)
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
                    if($hide == true)
                    {
                        // If forgive entry is found in curse logs, set entry to hidden
                        $curse->hidden = 1;
                        $curse->save();
                    }
                    else
                    {
                        // If forgive entry is found in curse logs, set entry to hidden
                        $curse->hidden = 0;
                        $curse->save();
                    }
                }
                else
                {
                    // If forgive entry is not found, log a notice on it
                    Log::channel('yumlog')->notice("Curse log entry not found for character: $character_id, ts: $parts[0], hash: $life->player_hash");
                }

                // Since life log entry is found, update Yumlog report with life information and set as verified
                try 
                {
                    self::setVerified($report, $life);
                } 
                catch (\Throwable $th) 
                {
                    Log::channel('yumlog')->error($th);
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
                        'hide' => $hide,
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

    public static function processPhexHash($line)
    {
        /*
            $parts[0] = timestamp | 1379020415 
            $parts[1] = type | phex_list
            $parts[2] = phex hash | 627474f34827254d1b373b3cb3b63691d8f7e445
            $parts[3] = phex name | DopiePanda
            $parts[4] = character ID | 7001138
            $parts[5] = character name | UGNE WUNDER

            1705115066 | phex_list | 627474f34827254d1b373b3cb3b63691d8f7e445 | DopiePanda | 7001138 | UGNE WUNDER
        */

        $parts = explode(' | ', $line);

        if (count($parts) == 6) 
        {
            $life = LifeLog::select('character_id', 'player_hash')->where('character_id', $parts[4])->where('type', 'death')->first();
        }

        try 
        {
            PhexHash::updateOrCreate(
                [
                    'olgc_name' => $parts[3],
                    'olgc_hash' => Str::take($parts[2], 8),
                ], 
                [
                    'olgc_hash_full' => $parts[2],
                    'player_hash' => $life ? $life->player_hash : null,
                    'character_id' => $life ? $life->character_id : null,
                ]
            );
        } 
        catch (\Throwable $th) 
        {
            Log::channel('yumlog')->error($th);
        }
    }
}
