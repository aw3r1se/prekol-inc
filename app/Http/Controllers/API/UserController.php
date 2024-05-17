<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function search(): AnonymousResourceCollection
    {
        $products = $this->get(User::class, function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });

        return UserResource::collection($products);
    }
}
