<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';

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
}