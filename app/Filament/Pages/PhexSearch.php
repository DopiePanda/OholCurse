<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PhexSearch extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'Phex Search';
    protected static ?string $navigationGroup = 'Accounts';

    protected static string $view = 'filament.pages.phex-search';
}
