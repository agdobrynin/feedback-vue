<?php

declare(strict_types=1);

use Core\{Response, Router};

$srcDirectory = __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
// Загрузка контейра зависимостей
$container = require $srcDirectory.'dependencies.php';
// Роутер основной
$router = new Router($container, new Response());
// массив с роутами и действиями
$routes = require $srcDirectory.'routes.php';
foreach ($routes as $uri => $action) {
    $router->add($uri, $action);
}

return $router;
