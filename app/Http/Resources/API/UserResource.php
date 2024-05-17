<?php

namespace App\Http\Resources\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'createdAt' => $this->created_at->format('Y-m-d h:i:s'),
            'updatedAt' => $this->created_at->format('Y-m-d h:i:s'),
            'deletedAt' => $this->deleted_at?->format('Y-m-d h:i:s'),
        ];
    }
}
