<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends BaseController
{
    public function index(): void
    {
        $loading_message = $this->showLoading();

        $products = (new Product())->all();

        $this->telegram->editMessage(
            "برای افزودن هریک از محصولات به سبد خرید، روی آن کلیک کنید",
            $this->prepareButtons($products),
            message_to_edit: $loading_message
        );
    }

    /**
     * @param $products
     * @return array
     */
    protected function prepareButtons($products): array
    {
        $buttons = [];
        foreach ($products as $product) {
            $buttons[] = [$product->name . " - " . priceFormat($product->price) => "/cart/add/{$product->id}"];
        }
        return $buttons;
    }
}