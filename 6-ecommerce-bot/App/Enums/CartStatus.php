<?php

namespace App\Enums;

enum CartStatus: int
{
    case ACTIVE = 1;
    case CLOSED = 2;
}
