<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Carbon\Carbon;

class Request extends Model
{
    protected string $table = 'requests';
    protected ?User $user = null;

    public function insert(int $user_id): ?Request
    {
        return $this->create([
            'user_id' => $user_id,
            'status' => RequestStatus::PENDING->value
        ]);
    }

    public function activeRequestForUser(int $user_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE `user_id`=:user_id AND `status`=:status ORDER BY `id` DESC LIMIT 1";

        return $this->db->prepare($sql, [
            'user_id' => $user_id,
            'status' => RequestStatus::PENDING->value
        ], __CLASS__)->find();
    }

    public function randomChat(int $except)
    {
        $sql = "SELECT * FROM {$this->table} WHERE `user_id`!=:user_id AND `status`=:status AND `created_at`>= :created_at ORDER BY RAND() LIMIT 1";

        $values = [
            'status' => RequestStatus::PENDING->value,
            'user_id' => $except,
            'created_at' => Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s')
        ];

        return $this->db->prepare($sql, $values, __CLASS__)->find();
    }

    public function user(): ?User
    {
        if (! $this->user) {
            $this->user = (new User)->find($this->user_id);
        }

        return $this->user;
    }

}