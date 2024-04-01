<?php

namespace App\Listeners;
 
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Log;

class AprilEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
    }

    public function handleUserLogin(Login $event): void 
    {
        Log::debug('User logged in');
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [AprilEventListener::class, 'handleUserLogin']
        );
    }
}
