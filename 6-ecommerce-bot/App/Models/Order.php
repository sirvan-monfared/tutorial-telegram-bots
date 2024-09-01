<?php

namespace App\Models;

use App\Enums\OrderStatus;

class Order extends Model
{
    protected string $table = 'orders';

    public function insert(int $user_id, int $cart_id, int $price, string $order_id, int $track_id): ?Order
    {
        return $this->create([
            'user_id' => $user_id,
            'cart_id' => $cart_id,
            'price' => $price,
            'order_id' => $order_id,
            'track_id' => $track_id,
            'support_id' => null,
            'status' => OrderStatus::NOT_PAID->value
        ]);
    }

    public function byTrackId(mixed $track_id): ?Order
    {
        $sql = "SELECT * FROM {$this->table} WHERE `track_id`=:track_id AND `status`=:status ORDER BY ID DESC LIMIT 1";

        return $this->db->prepare($sql, [
            'track_id' => $track_id,
            'status' => OrderStatus::NOT_PAID->value
        ], __CLASS__)->find();
    }
}