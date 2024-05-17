<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class DashboardServiceProvider extends ServiceProvider
{
    public static function boot(): void
    {
        Inertia::share('dashboard', config('dashboard'));
    }
}
