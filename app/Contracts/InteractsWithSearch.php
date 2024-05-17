<?php

namespace App\Contracts;

interface InteractsWithSearch
{
    public static function search($query = '', $callback = null);
}
