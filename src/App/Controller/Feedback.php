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

    public function editForm(): Response
    {
        return $this->response->setBody($this->view->render('edit'));
    }

    public function store(): Response
    {
        $responseDto = new ResponseDto();
        try {
            // Проверка на CSRF ключ
            (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
            $messageEntity = new Message();
            $messageModel = new Model($this->container->get(Db::class));
            $messageId = filter_input(\INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($messageId) {
                $messageEntity = $messageModel->get($messageEntity, $messageId);
                if(empty($messageEntity->id)) {
                    $message = sprintf('Попытка обновить сообщение по несуществующему id=%d', $messageId);
                    throw new \UnexpectedValueException($message);
                }
            }
            $messageEntity->fillFrom($_POST)->validate();
            $messageModel->save($messageEntity);
            $responseDto->success = true;
            $responseDto->message = sprintf('Спасибо, %s', $messageEntity->name);
        } catch (\Throwable $exception) {
            (new Csrf())->refresh()->setResponseHeader($this->response);
            $responseDto->fromException($exception);
        }

        return $this->response->setJson((array)$responseDto);
    }

    public function get(): Response
    {
        $responseDto = new ResponseDto();
        try {
            $messageId = filter_input(\INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $messageEntity = (new Model($this->container->get(Db::class)))->get(new Message(), $messageId);
            if ($messageId !== (int)$messageEntity->id) {
                $message = sprintf('Сообщение с id=%d не найдено', $messageId);
                throw new \UnexpectedValueException($message);
            }
            $responseDto->success = true;
            $responseDto->data['message'] = $messageEntity;
        } catch (\Throwable $exception) {
            $responseDto->fromException($exception);
        } finally {
            (new Csrf())->refresh()->setResponseHeader($this->response);
        }

        return $this->response->setJson((array)$responseDto);
    }

    public function delete(): Response
    {
        $responseDto = new ResponseDto();
        try {
            // Проверка на CSRF ключ
            (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
            $messageEntity = new Message();
            $messageModel = new Model($this->container->get(Db::class));
            $messageId = filter_input(\INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($messageId) {
                $messageEntity = $messageModel->get($messageEntity, $messageId);
                if(empty($messageEntity->id)) {
                    $message = sprintf('Попытка удалить сообщение по несуществующему id=%d', $messageId);
                    throw new \UnexpectedValueException($message);
                }
            }
            $messageModel->delete($messageEntity);
            $responseDto->success = true;
            $responseDto->message = sprintf('Сообщение с id=%d удалено', $messageEntity->id);
        } catch (\Throwable $exception) {
            (new Csrf())->refresh()->setResponseHeader($this->response);
            $responseDto->fromException($exception);
        }

        return $this->response->setJson((array)$responseDto);
    }
}
