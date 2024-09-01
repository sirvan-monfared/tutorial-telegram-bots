<?php

namespace App\Enums;

enum OrderStatus: int
{
    case NOT_PAID = 1;
    case PAID = 2;
    case CANCELED = 3;
}