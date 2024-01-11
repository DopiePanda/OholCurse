<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LiveSearchLeaderboards extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'Leaderboard Finder';

    protected static string $view = 'filament.pages.live-search-leaderboards';
}
