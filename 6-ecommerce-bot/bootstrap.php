<?php

use App\Core\App;
use App\Core\Container;
use eftec\bladeone\BladeOne;

$container = new Container();

$container->bind('blade', function () {
    return new BladeOne(base_path('views'), base_path('storage/cache'), BladeOne::MODE_DEBUG);
});

App::setContainer($container);
