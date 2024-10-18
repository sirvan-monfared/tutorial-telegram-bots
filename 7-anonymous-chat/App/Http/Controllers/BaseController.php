<?php

namespace App\Http\Controllers;

use App\Composers\CategoriesComposer;
use App\Core\App;
use App\Core\Container;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Category;
use eftec\bladeone\BladeOne;

class BaseController {

    protected $blade;

    public function __construct()
    {
        // TODO :: extract a csrf class with exception routes
        if (httpRequestMethod() !== 'GET' && ! CSRF::verify()) {
            Session::warning('نشست شما منقضی شده است');
            redirectBack();
        }


        $this->blade = App::resolve('blade');

        $this->setComposers();
    }

    protected function view($view, $params = []): void
    {
        echo $this->blade
            ->share([
                'errors' => Session::get('errors', [])
            ])
            ->run($view, $params);
    }

    protected function redirectToForm(Validator $validator): void
    {
        Session::flash('errors', $validator->errors());
        Session::flash('old', $_POST);
        Session::warning();

        redirectBack();
    }

    public function redirectWithErrors(): void
    {
        Session::flash('old', $_POST);
        Session::warning('مشکلی در اجرای عملیات پیش آمده است');

        redirectBack();
    }

    protected function setComposers(): void
    {
        $this->blade->composer('front.layouts._top_categories', CategoriesComposer::class);
    }

}