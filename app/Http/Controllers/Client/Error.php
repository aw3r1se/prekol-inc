<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class Error extends Controller
{
    public function __invoke($code): Response
    {
        return Inertia::render('Client/Error', [
            'code' => $code,
            'message' => Handler::messageByCode($code),
        ]);
    }
}
