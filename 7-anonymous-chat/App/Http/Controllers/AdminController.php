<?php

namespace App\Http\Controllers;

class AdminController extends BaseController
{
    public function index()
    {
        $this->view('admin.home');
    }
}