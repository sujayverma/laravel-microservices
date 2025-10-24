<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Exceptions\UnauthorizedPermissionException;

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
        Gate::define('view', function(User $user, $model) {
            if (!$user->hasAccess("view_{$model}")) {
                throw new UnauthorizedPermissionException();
            }
            return $user->hasAccess("view_{$model}");
        });

        Gate::define('edit', function(User $user, $model) {
            if (!$user->hasAccess("edit_{$model}")) {
                throw new UnauthorizedPermissionException();
            }
            return $user->hasAccess("edit_{$model}");
        });
    }
}
