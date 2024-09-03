<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case PROCESSING = 1;
    case PROCESSED = 2;
    case SENT = 3;
    case DELIVERED = 4;

    public function name(): string
    {
        return match ($this) {
            self::PROCESSING => '⌛ در حال پردازش',
            self::PROCESSED => '🔃 در حال ارسال',
            self::SENT => '📩 تحویل به اداره پست',
            self::DELIVERED => '✅ انصرف از پرداخت',
        };
    }
}
