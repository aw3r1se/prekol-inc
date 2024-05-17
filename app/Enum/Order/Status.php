<?php

namespace App\Enum\Order;

enum Status: int
{
    case NEW = 0;
    case PENDING = 1;
    case SUCCESSFUL = 2;

    case FAILED = 3;
}
