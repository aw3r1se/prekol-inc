<?php

namespace App\Http\Controllers\API;

use App\Contracts\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\CartResource;
use App\Models\Product;
use App\Services\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CartController extends Controller
{
    protected InteractsWithCart $service;

    public function __construct(InteractsWithCart $service)
    {
        $this->service = $service;
    }

    public function list(): AnonymousResourceCollection
    {
        $order = $this->service
            ->list(User\Processor::getUuid());

        return CartResource::collection($order?->products ?? []);
    }

    public function add(Product $product): Response
    {
        $this->service
            ->add($product, User\Processor::getUuid());

        return response()
            ->noContent();
    }

    public function sub(Product $product): Response
    {
        $this->service
            ->sub($product, User\Processor::getUuid());

        return response()
            ->noContent();
    }
}
