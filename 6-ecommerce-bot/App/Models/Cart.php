<?php

namespace App\Models;

use App\Enums\CartStatus;

class Cart extends Model
{
    protected string $table = 'carts';
    protected ?array $items = [];

    public function activeCartFor(int $user_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE `user_id`=:user_id AND `status`=:status ORDER BY `id` DESC LIMIT 1";

        return $this->db->prepare($sql, [
            'user_id' => $user_id,
            'status' => CartStatus::ACTIVE->value
        ], __CLASS__)->find();
    }

    public function items(): bool|array
    {
        if (empty($this->items)) {
            $this->items = (new CartItem)->for($this->id) ?? [];
        }

        return $this->items;
    }

    public function has(int $product_id): bool
    {
        var_dump($this->items());
        var_dump(array_filter($this->items(), fn($item) => (int) $item->product_id === $product_id));
        return (bool) array_filter($this->items(), fn($item) => (int) $item->product_id === $product_id);
    }

    public function insert(int $user_id, $status = null): ?Cart
    {
        return $this->create([
            'user_id' => $user_id,
            'status' => $status ?? CartStatus::ACTIVE->value
        ]);
    }

    public function addItem(int $product_id): ?CartItem
    {
        return (new CartItem)->insert($this->id, $product_id);
    }

    public function total(): ?int
    {
        return array_reduce($this->items(), function($sum, $item) {
            return $sum + $item->product()->price;
        });
    }

    public function close(): Cart
    {
        return $this->update([
            'status' => CartStatus::CLOSED->value
        ]);
    }
}