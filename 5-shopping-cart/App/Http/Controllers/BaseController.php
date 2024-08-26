<?php

namespace App\Http\Controllers;

use App\Composers\CategoriesComposer;
use App\Core\App;
use App\Core\Container;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Category;
use App\Services\TelegramService;
use eftec\bladeone\BladeOne;

class BaseController {

    protected TelegramService $telegram;

    public function init(TelegramService $telegram)
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function showLoading()
    {
        return $this->telegram->sendMessage("🔃 در حال دریافت اطلاعات ...");
    }
}