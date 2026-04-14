<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CartAddController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $parsed = $request->getParsedBody();
        $productId = isset($parsed['product_id']) ? (int) $parsed['product_id'] : 0;

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($productId > 0) {
            $_SESSION['cart'][] = $productId;
        }

        return redirect($response, '/menu');
    }
}
