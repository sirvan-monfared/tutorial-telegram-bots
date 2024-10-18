<?php

namespace App\Models;

use App\Enums\ChatStatus;

class Chat extends Model
{
    protected string $table = 'chats';
    protected ?User $hostUser = null;
    protected ?User $guestUser = null;

    public function start(int $host, int $guest): ?Chat
    {
        return $this->create([
            'host' => $host,
            'guest' => $guest,
            'status' => ChatStatus::ONGOING->value
        ]);
    }

    public function ongoingForUser(int $user_id): ?Chat
    {
        $sql = "SELECT * FROM {$this->table} WHERE `status`=:status AND (`host`=:user_id || `guest`=:user_id) ORDER BY `id` DESC LIMIT 1";

        return $this->db->prepare($sql, [
            'user_id' => $user_id,
            'status' => ChatStatus::ONGOING->value,
        ], __CLASS__)->find();
    }

    public function addMessage($content, $sender, $receiver): ?Conversation
    {
        return (new Conversation)->insert($this->id, $sender, $receiver, $content);
    }

    public function host(): ?User
    {
        if (! $this->hostUser) {
            $this->hostUser = (new User)->find($this->host);
        }

        return $this->hostUser;
    }

    public function guest(): ?User
    {
        if (! $this->guestUser) {
            $this->guestUser = (new User)->find($this->guest);
        }

        return $this->guestUser;
    }

    public function close(): void
    {
        $this->update([
            'status' => ChatStatus::CLOSED->value
        ]);
    }
}