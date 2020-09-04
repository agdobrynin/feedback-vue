<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ResponseMessageCollectionDto;
use App\Dto\ResponsePagesDto;
use App\Model\MessageCollection;
use Core\{BaseController, Csrf, Db, Response};

final class FeedbackList extends BaseController
{
    public function __invoke(): Response
    {
        return $this->response->setBody($this->view->render('list'));
    }

    public function pages(): Response
    {
        (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
        $responsePages = new ResponsePagesDto();
        $responsePages->pages = (new MessageCollection($this->container->get(Db::class)))->getTotalPages();
        $responsePages->success = true;

        return $this->response->setJson((array)$responsePages);
    }

    public function get(): Response
    {
        (new Csrf())->verify()->refresh()->setResponseHeader($this->response);
        $page = (int)filter_input(\INPUT_POST, 'page', FILTER_VALIDATE_INT);
        $result = (new MessageCollection($this->container->get(Db::class)))->getOnPage($page);
        $responseMessageCollection = new ResponseMessageCollectionDto();
        $responseMessageCollection->success = true;
        $responseMessageCollection->messageCollection = $result;
        return $this->response->setJson((array)$responseMessageCollection);
    }
}
