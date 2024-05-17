<?php

namespace App\Http\Controllers\API;

use App\Enum\Product\Currency;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function index(): array
    {
        return Currency::cases();
    }
}
