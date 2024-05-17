<?php

namespace App\Http\Resources\API;

use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin ProductPrice
 */
class ProductPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'currency' => Str::upper($this->currency),
            'createdAt' => $this->created_at->format('Y-m-d h:i:s'),
        ];
    }
}
