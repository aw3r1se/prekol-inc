<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Products/Products');
    }

    public function create(): Response
    {
        return Inertia::render('Products/ProductEdit');
    }

    public function edit(string $uuid): Response
    {
        return Inertia::render('Products/ProductEdit', [
            'id' => $uuid,
        ]);
    }
}
