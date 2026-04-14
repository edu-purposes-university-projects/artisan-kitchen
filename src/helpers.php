<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;

function app_base_path(): string
{
    return dirname(__DIR__);
}

function render_template(Response $response, string $name, array $data = []): Response
{
    $path = app_base_path() . '/templates/' . $name . '.php';
    if (!is_readable($path)) {
        $response->getBody()->write('Template not found.');
        return $response->withStatus(500);
    }
    ob_start();
    extract($data, EXTR_SKIP);
    include $path;
    $html = (string) ob_get_clean();
    $response->getBody()->write($html);
    return $response;
}

function redirect(Response $response, string $url, int $status = 302): Response
{
    return $response
        ->withHeader('Location', $url)
        ->withStatus($status);
}
