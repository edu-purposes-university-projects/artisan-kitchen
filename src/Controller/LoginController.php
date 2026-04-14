<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LoginController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $error = '';

        if ($request->getMethod() === 'POST') {
            $parsed = $request->getParsedBody();
            $username = isset($parsed['username']) ? trim((string) $parsed['username']) : '';
            $password = isset($parsed['password']) ? trim((string) $parsed['password']) : '';

            $users = [
                'admin' => ['password' => 'admin123', 'role' => 'admin'],
                'customer' => ['password' => 'customer123', 'role' => 'customer'],
            ];

            if (isset($users[$username]) && $users[$username]['password'] === $password) {
                $role = $users[$username]['role'];
                $token = create_jwt([
                    'username' => $username,
                    'role' => $role,
                ], 60 * 60 * 4);

                setcookie('auth_token', $token, [
                    'expires' => time() + 60 * 60 * 4,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);

                $target = $role === 'admin' ? '/admin/products' : '/menu';
                return redirect($response, $target);
            }

            $error = 'Invalid username or password.';
        }

        return render_template($response, 'login', ['error' => $error]);
    }
}
