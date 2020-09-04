<?php

use Core\{Config, Container, Csrf, Db, View};

$c = new Container();
// Конттейнер с конфигурацией
$c->set(Config::class, static function () {
    return new Config(require 'config.php');
});
// Контейнер с шаблонами
$c->set(View::class, static function () use ($c) {
    $view = new View($c->get(Config::class)->getViewDirectory());
    // Добавляю глобальную переменную $csrf в шаблоны
    $view->addGlobalData('csrf', new Csrf());

    return $view;
});
// Соединение с БД
$c->set(Db::class, static function () use ($c) {
    return Db::connect($c->get(Config::class));
});

return $c;
