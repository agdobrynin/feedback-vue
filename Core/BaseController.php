<?php

declare(strict_types=1);

namespace Core;

abstract class BaseController
{
    protected $container;
    protected $response;
    /** @var View */
    protected $view;

    public function __construct(Container $container, Response $response)
    {
        $this->container = $container;
        $this->view = $container->get(View::class);
        $this->response = $response;
    }
}
