<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // GATES

        // check if user is admin
        Gate::define('admin', function (): bool {
            return auth()->user()->role === 'admin';
        });

        // check if user is rh
        Gate::define('rh', function (): bool {
            return auth()->user()->role === 'rh';
        });

        // check if user is colaborator
        Gate::define('colaborator', function (): bool {
            return auth()->user()->role === 'colaborator';
        });

    }
}
