<?php

namespace App\Core\Middlewares;

class Admin
{
    public function handle()
    {
        if (!auth()->check() || ! auth()->user()->isAdmin()) {
            abort();
        }
    }
}