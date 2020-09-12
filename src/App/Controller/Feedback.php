<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\View as ViewHelper;
use Core\{BaseController, Response};

final class Feedback extends BaseController
{
    public function __invoke(): Response
    {
        $this->view->addGlobalData(
            ViewHelper::PAGE_PARAMS_KEY,
            (new ViewHelper())
                ->setTitle('Оставьте нам сообщение')
                ->setDescription('Форма для отправки сообщения')
                ->setKeywords('php, ajax, js')
        );
        return $this->response->setBody($this->view->render('form'));
    }

    public function editForm(): Response
    {
        $this->view->addGlobalData(
            ViewHelper::PAGE_PARAMS_KEY,
            (new ViewHelper())
                ->setTitle('Редактирование сообщения')
                ->setKeywords('vue component, webpack, html5, css')
        );
        return $this->response->setBody($this->view->render('edit'));
    }

}
