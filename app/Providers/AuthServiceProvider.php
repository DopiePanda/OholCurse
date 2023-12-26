<?php

namespace App\Providers;

use Spatie\Activitylog\Models\Activity;
use App\Policies\ActivityPolicy;

use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;

use Spatie\Permission\Models\Permission;
use App\Policies\PermissionPolicy;

use App\Models\User;
use App\Policies\UserPolicy;



use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class, // <- add this
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('system') ? true : null;
        });

        $this->registerPolicies();
    }
}
