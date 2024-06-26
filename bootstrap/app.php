<?php

use App\Http\Middleware\DefineCart;
use App\Http\Middleware\StampVisitors;
use Carbon\CarbonInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )->withSchedule(function (Schedule $schedule) {
        $schedule->command('telescope:prune')
            ->weeklyOn(CarbonInterface::SUNDAY, '3:00');
    })
    ->withMiddleware(function (Middleware $middleware) {
        $common = [
            StampVisitors::class,
        ];

        $middleware->web(append: Arr::collapse(
            [
                [
                    HandleInertiaRequests::class,
                    AddLinkHeadersForPreloadedAssets::class,
                ], $common,
            ],
        ));

        $middleware->api(append: Arr::collapse(
            [
                [
                    EnsureFrontendRequestsAreStateful::class,
                    DefineCart::class,
                ], $common,
            ],
        ));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
