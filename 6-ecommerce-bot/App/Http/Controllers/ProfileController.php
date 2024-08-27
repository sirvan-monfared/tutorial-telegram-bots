<?php

namespace App\Http\Controllers;

use App\Models\Command;

class ProfileController extends BaseController
{
    public function index(): void
    {
        $buttons = [
            ['ویرایش نام' => '/profile/name', 'ویرایش آدرس' => '/profile/address']
        ];

        $text = "مشخصات کاربری شما: \n\n";
        $text .= "نام: " . ($this->user->name ?? 'نامشخص') . "\n\n";
        $text .= "آدرس: " . ($this->user->address ?? 'نامشخص') . "\n\n";

        $this->telegram->editMessage($text, $buttons);
    }

    public function editName()
    {
        $this->storeCommand();

        $this->telegram->sendMessage("لطفا نام خود را وارد کنید");
    }

    public function updateName()
    {
        $this->user->updateName($this->telegram->text());

        $this->telegram->sendMessage("✅ ویرایش نام شما با موفقیت انجام شد");
    }

    public function editAddress()
    {
        $this->storeCommand();

        $this->telegram->sendMessage("لطفا آدرس خود را وارد کنید");
    }

    public function updateAddress()
    {
        $this->user->updateAddress($this->telegram->text());

        $this->telegram->sendMessage("✅ ویرایش آدرس شما با موفقیت انجام شد");
    }
}