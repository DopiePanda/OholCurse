<?php

namespace App\Filament\Pages;

use Auth;

use Filament\Pages\Page;
use Saade\FilamentLaravelLog\Pages\ViewLog;

class CustomLogs extends ViewLog
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function __construct()
    {
        if(!auth()->user()->hasRole('system'))
        {
            return abort(403);
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Authentication';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-bug-ant';
    }

    public static function getNavigationLabel(): string
    {
        return 'System logs';
    }

    public static function getSlug(): string
    {
        return 'system-logs';
    }

    public function getTitle(): string
    {
        return __('log::filament-laravel-log.page.title');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->canManageSettings();
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return auth()->user()->hasRole('system');
    }
}
