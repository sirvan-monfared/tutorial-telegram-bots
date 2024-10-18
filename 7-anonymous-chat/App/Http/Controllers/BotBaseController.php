<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Models\User;
use App\Services\TelegramService;

class BotBaseController
{
    protected TelegramService $telegram;
    protected ?User $user = null;

    public function init(TelegramService $telegram, User $user)
    {
        $this->telegram = $telegram;

        $this->user = $user;

        return $this;
    }

    public function showLoading()
    {
        return $this->telegram->sendMessage("🔃 در حال دریافت اطلاعات ...");
    }

    public function closeCommand(Command $command): static
    {
        $command->close();

        return $this;
    }

    protected function storeCommand(?string $command = null): ?Command
    {
        return (new Command)->create([
            'user_id' => $this->user->id,
            'command' => $command ?? $this->telegram->text(),
            'completed' =>  CommandStatus::PENDING->value
        ]);
    }
}