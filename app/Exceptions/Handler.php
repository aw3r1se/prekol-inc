<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e): \Inertia\Response|Response
    {
        if ($this->isHttpException($e)) {
            if ($e instanceof NotFoundHttpException) {
                return Inertia::render('Client/404');
            }
        }

        return parent::render($request, $e);
    }
}
