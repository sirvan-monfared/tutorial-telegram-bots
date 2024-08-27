<?php

namespace App\Models;

use App\Enums\CommandStatus;

class Command extends Model
{
    protected string $table = 'commands';

    public function latestUserActiveCommand(int $user_id): ?Command
    {
        $sql = "SELECT * FROM {$this->table} where user_id=:user_id AND completed=0 ORDER BY `id` DESC LIMIT 1";

        return $this->db->prepare($sql, [
            'user_id' => $user_id
        ], __CLASS__)->find();
    }

    public function close(): Command
    {
        return $this->update([
            'completed' => CommandStatus::COMPLETED->value
        ]);
    }
}