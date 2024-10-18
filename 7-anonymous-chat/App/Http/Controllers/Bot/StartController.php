<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\BotBaseController;

class StartController extends BotBaseController
{
    public function index()
    {
        $buttons = [
            ['چت تصادفی' => '/chat/random'],
        ];
        $text = " به ربات چت تصادفی لاراپلاس خوش آمدید 💬 \n\n";
        $text .= "لطفا یکی از گزینه های زیر را انتخاب کنید";

        $this->telegram->sendMessage($text, $buttons);
    }
}