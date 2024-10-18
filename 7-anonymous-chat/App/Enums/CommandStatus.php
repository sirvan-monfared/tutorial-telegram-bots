<?php

namespace App\Enums;

enum CommandStatus: int
{
    case PENDING = 0;
    case COMPLETED = 1;
}
