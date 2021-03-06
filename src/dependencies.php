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
    // Добавляю глобальный класс Helper\View $pageParams для настройки мета данных в html странице
    $view->addGlobalData(App\Helper\View::PAGE_PARAMS_KEY, new App\Helper\View());
    // Добавляю переменную $jsBandlerDirectory расположения js бандлов в шаблонизатор
    $rootDirectoryWebApplication = dirname(__DIR__. 2).DIRECTORY_SEPARATOR.'public';
    $jsBandlerDirectory = $rootDirectoryWebApplication.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR;
    $view->addGlobalData('jsBandlerDirectory', $jsBandlerDirectory);

    return $view;
});
// Соединение с БД
$c->set(Db::class, static function () use ($c) {
    return Db::connect($c->get(Config::class));
});

return $c;
