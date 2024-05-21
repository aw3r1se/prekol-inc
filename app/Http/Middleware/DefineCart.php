<?php

namespace App\Http\Middleware;

use App\Contracts\InteractsWithCart;
use Closure;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DefineCart
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->singleton(
            InteractsWithCart::class,
            Auth::user()
                ? Cart\Explicit::class
                : Cart\Implicit::class,
        );

        return $next($request);
    }
}
