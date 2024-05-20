<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\API;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProductController extends Controller
{
    public function search(): AnonymousResourceCollection
    {
        dd(Auth::user());

        $products = $this->get(Product::class, function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });

        return API\ProductResource::collection($products);
    }

    public function show(Product $product): API\ProductResource
    {
        $product->load([
            'media',
            'tags',
            'prices' => function (HasMany $builder) {
                $builder->orderByDesc('id');
            },
        ]);

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
}
