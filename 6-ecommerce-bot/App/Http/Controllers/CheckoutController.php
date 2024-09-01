<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Exceptions\GatewayException;
use App\Models\Order;
use App\Services\ZibalGateway;

class CheckoutController extends BaseController
{
    public function pay()
    {
        $loading_message = $this->showLoading();

        $cart = $this->user->findOrCreateActiveCart();
        $amount = $cart->total() * 10;
        if (! $cart->items() || $amount <= 0) {
            return $this->telegram->editMessage("هیچ محصولی در سبد خرید وجود ندارد", message_to_edit: $loading_message);
        }

        // TODO :: connect to gateway
        $this->telegram->editMessage("در حال دریافت اطلاعات پرداخت...", message_to_edit: $loading_message);

        try {
            $order_id = generateRandom(10);
            $gateway = (new ZibalGateway())->request($amount, $order_id);

            $text = "برای اتصال به درگاه پرداخت روی لینک زیر کلیک کنید \n\n";
            $text .= $gateway->paymentUrl();

            $order = (new Order)->insert(
                user_id: $this->user->id,
                cart_id: $cart->id,
                price: $amount,
                order_id: $order_id,
                track_id: $gateway->trackId()
            );

            var_dump($order);

            $this->telegram->editMessage($text, message_to_edit: $loading_message);

        } catch (GatewayException $e) {
            return $this->telegram->editMessage("❌ {$e->getMessage()} ❌", message_to_edit: $loading_message);
        }
    }
}