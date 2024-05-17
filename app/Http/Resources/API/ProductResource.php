<?php

namespace App\Http\Resources\API;

use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $prices = $this->currentPrices;
        $price = $prices
            ->firstWhere('currency', config('app.main_currency')->value);

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'image' => MediaResource::make($this->image),
            'media' => MediaResource::collection($this->appendMedia()),
            'tags' => $this->appendTags(),
            'relevantPrice' => ProductPriceResource::make($price),
            'prices' => ProductPriceResource::collection($this->currentPrices),
            'createdAt' => $this->created_at->format('Y-m-d h:i:s'),
            'updatedAt' => $this->created_at->format('Y-m-d h:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d h:i:s'),
        ];
    }

    /**
     * @return Collection<Media>|array
     */
    protected function appendMedia(): Collection|array
    {
        return $this->relationLoaded('media')
            ? $this->media
            : [];
    }

    /**
     * @return Collection<ProductTag>|array
     */
    protected function appendTags(): Collection|array
    {
        return $this->relationLoaded('tags')
            ? $this->tags
            : [];
    }
}