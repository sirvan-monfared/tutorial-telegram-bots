<?php

namespace App\Models;

class CartItem extends Model
{
    protected string $table = 'cart_items';
    protected bool $timestamps = false;

    public ?Product $product = null;

    public function product(): ?Product
    {
        if (! $this->product) {
            $this->product = (new Product)->find($this->product_id);
        }

        return $this->product;
    }

    public function for(int $cart_id): bool|array
    {
        return $this->whereAll('cart_id', $cart_id);
    }

    public function insert(int $cart_id, int $product_id): ?CartItem
    {
        return $this->create([
            'cart_id' => $cart_id,
            'product_id' => $product_id
        ]);
    }
}