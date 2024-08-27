<?php

namespace App\Http\Controllers;

use App\Models\User;

class StartController extends BaseController
{
    public function handle(): void
    {
        $buttons = [
            ['مشاهده محصولات' => '/products', 'مشاهده سبدخرید' => '/cart'],
            ['اطلاعات کاربری' => '/profile']
        ];

        $this->telegram->sendMessage("یکی از گزینه های زیر را نتخاب کنید", $buttons);
    }
}