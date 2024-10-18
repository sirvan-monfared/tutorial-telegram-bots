<?php

namespace App\Enums;

enum RequestStatus: int
{
    case PENDING = 1;
    case CLOSED = 2;
}
