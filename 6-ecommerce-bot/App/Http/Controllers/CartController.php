<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;

class CartController extends BaseController
{
    public function index()
    {
        $loading_message = $this->showLoading();

        $cart = $this->user->findOrCreateActiveCart();
        $items = $cart->items();

        if (empty($items)) {
            return $this->telegram->editMessage("محصولی در سبد خرید شما وجود ندارد", message_to_edit: $loading_message);
        }
        $text = "برای حذف هریک از آیتم ها از سبد خرید خود، لطفا روی آن کلیک کنید \n\n";

        $text .= "مجموع سبد خرید شما: " . priceFormat($cart->total());


        return $this->telegram->editMessage(
            $text,
            $this->prepareCartListButtons($items),
            message_to_edit: $loading_message
        );
    }


    public function store(string $command, int $param)
    {
        $loading_message = $this->showLoading();

        $product = (new Product)->find($param);

        if (!$product) {
            return $this->telegram->editMessage("❌دستور وارد شده اشتباه است❌", message_to_edit: $loading_message);
        }

        $cart = $this->user->findOrCreateActiveCart();

        if ($cart->has($product->id)) {
            return $this->telegram->editMessage("محصول مورد نظر در سبد خرید شما موجود است", message_to_edit: $loading_message);
        }

        $cart->addItem($product->id);

        $this->telegram->editMessage("✅محصول {$product->name} به سبد خرید اضافه شد✅", message_to_edit: $loading_message);
    }

    public function destroy(string $command, int $id)
    {
        $loading_message = $this->showLoading();

        $item = (new CartItem)->find($id);
        $cart = $this->user->findOrCreateActiveCart();

        if (! $item || $item->cart_id !== $cart->id) {
            return $this->telegram->editMessage("❌ دستور وارد شده معتبر نیست❌", message_to_edit: $loading_message);
        }

        $item->delete();
        $this->telegram->editMessage("✅محصول {$item->product()->name} از سبد خرید شما حذف شد", message_to_edit: $loading_message);
    }

    /**
     * @param bool|array $items
     * @return array
     */
    protected function prepareCartListButtons(bool|array $items): array
    {
        $buttons = [];
        foreach ($items as $item) {
            $buttons[] = ["🗑️ " . $item->product()->name . " - " . priceFormat($item->product()->price) => "/cart/delete/{$item->id}"];
        }
        return $buttons;
    }
}