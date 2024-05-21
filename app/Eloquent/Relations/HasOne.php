<?php

namespace App\Eloquent\Relations;

use App\Eloquent\Relations\Traits\CanBeOneOfMany;
use Illuminate\Database\Eloquent\Relations as Vendor;

class HasOne extends Vendor\HasOne
{
    use CanBeOneOfMany;
}
