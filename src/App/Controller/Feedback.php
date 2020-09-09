<?php

declare(strict_types=1);

namespace App\Controller;

use Core\{BaseController, Response};

final class Feedback extends BaseController
{
    public function __invoke(): Response
    {
        return $this->response->setBody($this->view->render('form'));
    }

    public function editForm(): Response
    {
        return $this->response->setBody($this->view->render('edit'));
    }

}
