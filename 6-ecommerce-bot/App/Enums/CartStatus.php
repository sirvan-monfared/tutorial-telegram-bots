<?php

namespace App\Enums;

enum CartStatus: int
{
    case ACTIVE = 1;
    case PAID = 2;
    case DISABLED = 3;
}
