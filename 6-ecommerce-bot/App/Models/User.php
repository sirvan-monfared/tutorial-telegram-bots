<?php

namespace App\Models;

use App\Enums\CartStatus;

class User extends Model
{
    protected string $table = 'users';

    protected array $orders = [];

    public function updateName(string $name): User
    {
        return $this->update([
            'name' => $name
        ]);
    }

    public function updateAddress(string $address): User
    {
        return $this->update([
            'name' => $address
        ]);
    }

    public function latestActiveCommand(): ?Command
    {
        return (new Command)->latestUserActiveCommand($this->id);
    }

    public function findOrCreateActiveCart()
    {
        $cart = (new Cart)->activeCartFor($this->id);

        if (! $cart) {
            $cart = (new Cart)->insert(user_id: $this->id);
        }

        return $cart;
    }

    public function orders()
    {
        if (! $this->orders) {
            $this->orders = (new Order)->forUser($this->id);
        }

        return $this->orders;
    }
}