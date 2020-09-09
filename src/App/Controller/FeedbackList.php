<?php

declare(strict_types=1);

namespace App\Controller;

use Core\{BaseController, Response};

final class FeedbackList extends BaseController
{
    public function __invoke(): Response
    {
        return $this->response->setBody($this->view->render('list'));
    }
}
