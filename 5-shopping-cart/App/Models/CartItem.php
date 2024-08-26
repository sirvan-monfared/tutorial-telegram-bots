<?php

namespace App\Models;

class CartItem extends Model
{
    protected string $table = 'cart_items';
    protected bool $timestamps = false;

    public ?Product $product = null;

    public function byChatIdAndProductId(int $chat_id, int $product_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE chat_id=:chat_id AND product_id=:product_id LIMIT 1";

        return $this->db->prepare($sql, [
            'chat_id' => $chat_id,
            'product_id' => $product_id
        ], __CLASS__)->find();
    }


    public function product(): ?Product
    {
        if (! $this->product) {
            $this->product = (new Product)->find($this->product_id);
        }

        return $this->product;
    }

}