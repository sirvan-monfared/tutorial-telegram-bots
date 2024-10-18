<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Model;
use App\Models\Product;

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->view('home');
    }
}
