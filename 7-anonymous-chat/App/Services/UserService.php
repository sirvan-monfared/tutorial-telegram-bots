<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public static function findOrCreateUser(int $chat_id): ?User
    {
        $user = (new User)->where('chat_id', $chat_id);

        if  (! $user) {
            $user = (new User)->create([
                'chat_id' => $chat_id
            ]);
        }

        return $user ?? null;
    }
}