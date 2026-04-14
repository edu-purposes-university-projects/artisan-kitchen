<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LogoutController
{
    public function __invoke(Request $request, Response $response): Response
    {
        setcookie('auth_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        return redirect($response, '/');
    }
}
