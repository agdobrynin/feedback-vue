<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\View as ViewHelper;
use Core\{BaseController, Response};

final class FeedbackList extends BaseController
{
    public function __invoke(): Response
    {
        $this->view->addGlobalData(
            ViewHelper::PAGE_PARAMS_KEY,
            (new ViewHelper())
                ->setTitle('Список Feedback сообщений')
                ->setDescription('Постраничный список сообщений, для загрузки используется ajax')
                ->setKeywords('php, mvc, router, vuejs, pug template')
        );
        return $this->response->setBody($this->view->render('list'));
    }
}
