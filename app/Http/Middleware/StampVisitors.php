<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class StampVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $stamp = $request->cookie('stamp');
        $request->offsetSet(
            'stamp',
                $stamp
                ?? ($stamp = Str::uuid())
        );

        $stamp = Cookie::make('stamp', $stamp, 10080);

        /** @var Response */
        return $next($request)->cookie($stamp);
    }
}
