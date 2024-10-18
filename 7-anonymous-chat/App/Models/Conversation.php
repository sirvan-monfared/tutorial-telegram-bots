<?php

namespace App\Models;

class Conversation extends Model
{
    protected string $table = 'conversations';

    public function insert($chat_id, $sender, $receiver, $content)
    {
        return $this->create([
            'chat_id' => $chat_id,
            'sender' => $sender,
            'receiver' => $receiver,
            'content' => $content
        ]);
    }
}