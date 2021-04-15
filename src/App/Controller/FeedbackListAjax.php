<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\{ResponseMessageCollectionDto, ResponsePagesDto};
use App\Model\MessageCollection;
use Core\{BaseController, Csrf, Db, Response};

final class FeedbackListAjax extends BaseController
{

    public function pages(): Response
    {
        $responsePages = new ResponsePagesDto();
        try {
            (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
            $responsePages->pages = (new MessageCollection($this->container->get(Db::class)))->getTotalPages();
            $responsePages->success = true;
        } catch (\Throwable $exception) {
            (new Csrf())->refresh()->setResponseHeader($this->response);
            $responsePages->fromException($exception);
        }

        return $this->response->setJson((array)$responsePages);
    }

    public function get(): Response
    {
        $responseMessageCollection = new ResponseMessageCollectionDto();
        try {
            (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
            $page = (int)filter_input(\INPUT_POST, 'page', FILTER_VALIDATE_INT);
            $result = (new MessageCollection($this->container->get(Db::class)))->getOnPage($page);
            $responseMessageCollection->success = true;
            $responseMessageCollection->messageCollection = $result;
        } catch (\Throwable $exception) {
            (new Csrf())->refresh()->setResponseHeader($this->response);
            $responseMessageCollection->fromException($exception);
        }
        return $this->response->setJson((array)$responseMessageCollection);
    }
}
