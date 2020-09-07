<?php

declare(strict_types=1);

namespace Core;

final class Router
{

    public const DELIMITER_ACTION_SYMBOL = '@';

    private $uri;
    private $container;
    private $routes;
    private $response;
    private $isAjax;

    public function __construct(Container $container, Response $response)
    {
        $this->uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->container = $container;
        $this->response = $response;

        $oldAjaxRequestHeader = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        $isOldAjaxRequestHeader = 'xmlhttprequest' === strtolower($oldAjaxRequestHeader);
        $acceptRequestHeader = $_SERVER['HTTP_ACCEPT'] ?? $_SERVER['ACCEPT'] ?? '';
        $isAcceptJsonResponse = 'application/json' === strtolower($acceptRequestHeader);
        $this->isAjax = $isOldAjaxRequestHeader || $isAcceptJsonResponse;

    }

    public function isAjax(): bool
    {
        return $this->isAjax;
    }

    public function add(string $route, $callable): self
    {
        $this->routes[$route] = $callable;

        return $this;
    }

    public function execute(): Response
    {
        $action = $this->routes[$this->uri] ?? null;
        if ($action) {
            if (is_string($action)) {
                if (false !== strpos($action, self::DELIMITER_ACTION_SYMBOL)) {
                    $controller = strstr($action, self::DELIMITER_ACTION_SYMBOL, true);
                    $controllerMethod = substr(strrchr($action, self::DELIMITER_ACTION_SYMBOL), 1);
                    $class = [new $controller($this->container, $this->response), $controllerMethod];
                } else {

                    $class = new $action($this->container, $this->response);
                }
                return call_user_func($class);
            }

            if (is_callable($action)) {
                return new $action;
            }
        }

        return (new Response())->setStatusCode(404, 'Route not found')->setBody('Page not found at '.$this->uri);
    }
}
