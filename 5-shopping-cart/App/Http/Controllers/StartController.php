<?php

namespace App\Http\Controllers;

class StartController extends BaseController
{
    public function handle(): void
    {
        $buttons = [
            ['مشاهده محصولات' => '/products', 'مشاهده سبدخرید' => '/cart']
        ];

        $this->telegram->sendMessage("یکی از گزینه های زیر را نتخاب کنید", $buttons);
    }
}