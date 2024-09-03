<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Models\Order;
use http\Message;

class OrderController extends BaseController
{
    public function index()
    {
        $loading = $this->showLoading();

        $orders = $this->user->orders();

        $keyboard = [];
        foreach ($orders as $order) {
            $text = "قیمت:" . priceFormat($order->price) . "- ". OrderStatus::from($order->status)->name()  ." - " . shamsi($order->updated_at);
            $keyboard[] = [$text => "/order/{$order->id}"];
        }

        $text = "لطفا روی یکی از سفارش های زیر کلیک کنید";

        $this->telegram->editMessage($text, $keyboard, message_to_edit: $loading);
    }

    public function show(string $command, string $order_id)
    {
        $loading = $this->showLoading();
        $order = (new Order)->find($order_id);

        if (! $order || (int) $order->user_id !== (int) $this->user->id) {
            return $this->telegram->editMessage("❌دستور وارد شده نامعتبر است❌", message_to_edit: $loading);
        }

        $text = "اطلاعات سفارش انتخاب شده بدین شرح است: \n\n";

        $text .= "قیمت: " . priceFormat($order->price) . "\n\n";
        $text .= "وضعیت پرداخت: " . OrderStatus::from($order->status)->name() . "\n\n";
        $text .= "وضعیت سفارش: " . ShipmentStatus::from($order->shipment_status)->name() . "\n\n";
        $text .= "----------------------------- \n\n";
        $text .= "محصولات موجود در سفارش: \n\n";

        foreach ($order->cart()->items() as $item) {
            $text .= " - {$item->product()->name} \n\n";
        }

        $keyboard = [
            ["بازگشت" => '/orders']
        ];

        $this->telegram->editMessage($text, $keyboard, message_to_edit: $loading);
    }
}