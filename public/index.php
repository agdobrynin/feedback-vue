<?php

declare(strict_types=1);

use Core\{Router, Response, Csrf};

require_once __DIR__.'/../vendor/autoload.php';
// Старт сессий
session_start();

try {
    /** @var Router $router */
    $router = require __DIR__.'/../bootstrap.php';
    $response = $router->execute();
} catch (\Throwable $exception) {
    $response = new Response();
    // Всегда в заголовке отдавать CSRF токен.
    (new Csrf())->setResponseHeader($response);
    if ($router->isAjax()) {
        $data = [
            'success' => false,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTrace(),
        ];
        $response->setJson($data);
    } else {
        $body = <<< EOL
        <!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Error</title></head><body>
            <h1>{$exception->getMessage()}</h1>
            <h4>{$exception->getFile()}:{$exception->getLine()}</h4>
            <pre>{$exception->getTraceAsString()}</pre>
        </body></html>
        EOL;
        $response->setBody($body);
    }

    $response = $response->setStatusCode(500, 'Server Error!');
} finally {
    $response->emit();
}
