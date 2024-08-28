<?php

namespace App\Http\Controllers;

class CheckoutController extends BaseController
{
    public function pay()
    {
        $loading_message = $this->showLoading();

        $cart = $this->user->findOrCreateActiveCart();

        if (! $cart->items() || $cart->total() <= 0) {
            return $this->telegram->editMessage("هیچ محصولی در سبد خرید وجود ندارد", message_to_edit: $loading_message);
        }

        // TODO :: connect to gateway
        $this->telegram->editMessage("در حال اتصال به درگاه پرداخت ...", message_to_edit: $loading_message);
    }
}