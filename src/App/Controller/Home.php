<?php

declare(strict_types=1);

namespace App\Controller;

use Core\BaseController;

final class Home extends BaseController
{
    public function __invoke()
    {
        return $this->response->setBody($this->view->render('index'));
    }
}
