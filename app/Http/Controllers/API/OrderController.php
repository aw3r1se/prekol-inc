<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\API;
use App\Models\Product;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function search(): AnonymousResourceCollection
    {
        $orders = $this->get(Order::class);

        return API\OrderResource::collection($orders);
    }

    /**
     * @throws Exception
     */
    public function addToOrder(Product $product): Response
    {
        $user = Auth::user();
        $stamp = request()->offsetGet('stamp');

        dd($user);

        $this->service
            ->addProduct($product, $user, $stamp);

        return response()
            ->noContent();
    }
}
