<?php

namespace App\Http\Controllers\API;

use App\Contracts\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\API;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use App\Services\User;

class ProductController extends Controller
{
    protected InteractsWithCart $cartService;

    public function __construct(InteractsWithCart $cartService)
    {
        $this->cartService = $cartService;
    }

    public function search(): AnonymousResourceCollection
    {
        $products = $this->get(Product::class, function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });

        return API\ProductResource::collection($products);
    }

    /**
     * @throws Exception
     */
    public function show(Product $product): API\ProductResource
    {
        $product->load([
            'media',
            'tags',
            'prices' => function (HasMany $builder) {
                $builder->orderByDesc('id');
            },
        ]);

        $product->is_in_order = $this->cartService
            ->isProductInCart(
                $product,
                User\Processor::getUuid(),
            );

        return API\ProductResource::make($product);
    }

    public function store(Requests\API\Product\StoreRequest $request): Response
    {
        $data = $request->validated();

        Product::query()
            ->create($data);

        return response(status: 201);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Product $product, Requests\API\Product\UpdateRequest $request): Response
    {
        $data = $request->validated();
        $product->update($data);

        foreach ($data['upload'] ?? [] as $media) {
            $product->addMediaFromDisk($media, 'temp')
                ->toMediaCollection('products', 'products');
        }

        return response(status: 200);
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()
            ->noContent();
    }

    public function restore(string $product_uuid): Response
    {
        Product::withTrashed()
            ->find($product_uuid)
            ->restore();

        return response()
            ->noContent();
    }

    public function addPrice(
        Product $product,
        Requests\API\Product\PriceRequest $request,
    ): Response {
        $product->prices()
            ->create($request->validated());

        return response(status: 201);
    }

    public function upload(Product $product, Request $request): false|string
    {
        return $request->file('file')
            ->storeAs(
                null,
                Str::random(24),
                [ 'disk' => 'temp' ],
            );
    }

    public function addToCart(Product $product): Response
    {
        $this->cartService
            ->addProductToCart(
                $product,
                User\Processor::getUuid(),
            );

        return response()
            ->noContent();
    }

    public function removeFromCart(Product $product): Response
    {
        $this->cartService
            ->removeProductFromCart(
                $product,
                User\Processor::getUuid(),
            );

        return response()
            ->noContent();
    }
}
