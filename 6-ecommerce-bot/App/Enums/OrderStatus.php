<?php

namespace App\Enums;

enum OrderStatus: int
{
    case NOT_PAID = 1;
    case PAID = 2;
    case CANCELED = 3;

    public function name(): string
    {
        return match ($this) {
            self::NOT_PAID => '⌛ در انتظار پرداخت',
            self::PAID => '✅ پرداخت شده',
            self::CANCELED => '🚫 انصرف از پرداخت',
        };
    }
}