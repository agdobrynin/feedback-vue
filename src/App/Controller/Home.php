<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\View as ViewHelper;
use Core\BaseController;

final class Home extends BaseController
{
    public function __invoke()
    {
        $data[ViewHelper::PAGE_PARAMS_KEY] = (new ViewHelper())
                ->setTitle('Тестовое задание Php + Vue Js')
                ->setDescription('Сделать форму обратной связи на чистом PHP, Mysql (sqlite), VueJs')
                ->setKeywords('php, ооп, mvc, vue js, pug template, webpack, html5, css, boostrap 3.4');


        return $this->response->setBody($this->view->render('index', $data));
    }
}
