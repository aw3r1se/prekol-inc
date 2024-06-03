<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e): \Inertia\Response|Response
    {
        if ($e instanceof HttpException) {
            return redirect()
                ->route('client.error', [
                    'code' => $e->getStatusCode(),
                ]);
        }

        return parent::render($request, $e);
    }

    public static function messageByCode(int $code): string
    {
        return match ($code) {
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            419 => 'Session expired',
            default => 'Server error',
        };
    }
}
