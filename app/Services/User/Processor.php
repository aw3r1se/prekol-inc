<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Processor
{
    public static function getUuid(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user
            ? $user->uuid
            : request()->session()
                ->getId();
    }
}
