<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameObjectResource\Pages;
use App\Filament\Resources\GameObjectResource\RelationManagers;
use App\Models\GameObject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use Filament\Tables\Actions\Action;

class GameObjectResource extends Resource
{
    protected static ?string $model = GameObject::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

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
                TextColumn::make('id')
                ->sortable(),
                TextColumn::make('name')
                ->sortable()
                ->searchable(),
            ])
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(50)
            ->filters([
                SelectFilter::make('id')
                ->options(GameObject::all()->pluck('id'))
                ->searchable(),
            ])
            ->actions([
            ])
            ->headerActions([
                Action::make('import')
                ->form([
                    FileUpload::make('objects_file')
                    ->required()
                    ->acceptedFileTypes(['text/plain'])
                    ->preserveFilenames()
                    ->storeFiles(false)
                ])
                ->label('Import Game Objects')
                ->action(function (array $data, array $arguments): void {
                    $path = $data['objects_file']->getRealPath();
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
            'index' => Pages\ListGameObjects::route('/'),
        ];
    }

    public static function processFile($path)
    {
        $start_time = microtime(true);

        try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                File::lines($path)->each(function ($line) {
                    
                    $line = explode(' ', $line, 2);

                    if(count($line) == 2 && $line[0] > 0 && $line[0] < 9999)
                    {
                        GameObject::updateOrCreate(
                            [
                                'id' => $line[0],
                            ],
                            [
                                'name' => $line[1],
                            ]
                        );
                    }
                });

                // Commit the DB transaction
                DB::commit();

                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::info("Game objects updated in $time seconds");

            }catch(\Exception $e) {
            
                // Rollback DB transaction
                DB::rollback();

                // Log exception message
                Log::error('Exception returned when inserting the GAME OBJECTS');
                Log::error($e->getMessage());
            }
    }
}
