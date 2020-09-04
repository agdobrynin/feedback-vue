<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ResponseDto;
use App\Entity\Message;
use App\Model\Message as Model;
use Core\{BaseController, Csrf, Db, Response};

final class Feedback extends BaseController
{
    public function __invoke(): Response
    {
        return $this->response->setBody($this->view->render('form'));
    }

    public function store(): Response
    {
        $data = new ResponseDto();
        try {
            // Проверка на CSRF ключ
            (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
            $messageEntity = (new Message())->fillFrom($_POST)->validate();
            (new Model($this->container->get(Db::class)))->save($messageEntity);
            $data->success = true;
            $data->message = sprintf('Спасибо, %s', $messageEntity->name);
        } catch(\Throwable $exception) {
            (new Csrf())->refresh()->setResponseHeader($this->response);
            $data->success = false;
            $data->message = $exception->getMessage();
        }

        return $this->response->setJson((array)$data);
    }
}
