<?php

namespace App\Http\Resources\API;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image' => MediaResource::make($this->image),
            'price' => $this->price,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
