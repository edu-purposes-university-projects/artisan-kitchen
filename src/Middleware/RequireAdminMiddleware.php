<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

final class RequireAdminMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = current_user();

        if ($user === null) {
            return $this->redirect('/login');
        }

        if (($user['role'] ?? '') === 'customer') {
            return $this->redirect('/menu');
        }

        if (($user['role'] ?? '') !== 'admin') {
            return $this->redirect('/login');
        }

        return $handler->handle($request);
    }

    private function redirect(string $path): Response
    {
        $response = new SlimResponse(302);
        return $response->withHeader('Location', $path);
    }
}
