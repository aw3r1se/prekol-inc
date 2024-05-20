<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class Cart extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Client/Cart');
    }
}
