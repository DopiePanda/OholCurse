<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Component;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Component::macro('alert', function ($type, $message) {
            // $this will refer to the component class
            // not to the AppServiceProvider
            $this->dispatch('alert', type: $type, message: $message);
        });
    }
}
