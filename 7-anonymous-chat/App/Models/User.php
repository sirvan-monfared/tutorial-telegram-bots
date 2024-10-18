<?php

namespace App\Models;

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

    public function findOrCreateRequest(): Request
    {
        $request = (new Request)->activeRequestForUser($this->id);

        if (! $request) {
            $request = (new Request)->insert($this->id);
        }

        return $request;
    }

    public function ongoingChat(): ?Chat
    {
        return (new Chat)->ongoingForUser($this->id);
    }

    public function deleteLatestRequest(): void
    {
        $request = $this->findOrCreateRequest();

        $request->delete();
    }

}