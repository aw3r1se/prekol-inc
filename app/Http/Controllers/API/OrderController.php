<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\API;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function search(): AnonymousResourceCollection
    {
        $orders = $this->get(Order::class);

        return API\OrderResource::collection($orders);
    }
}
