<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateProductController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $parsed = $request->getParsedBody();
        $name = isset($parsed['name']) ? trim((string) $parsed['name']) : '';
        $description = isset($parsed['description']) ? trim((string) $parsed['description']) : '';
        $price = isset($parsed['price']) ? trim((string) $parsed['price']) : '';
        $imageUrl = isset($parsed['image_url']) ? trim((string) $parsed['image_url']) : '';

        $db = Database::connection();
        if ($db && $name !== '' && $price !== '') {
            $image = $imageUrl !== '' ? $imageUrl : null;
            pg_query_params(
                $db,
                'INSERT INTO products (name, description, price, image_url) VALUES ($1, $2, $3, $4)',
                [$name, $description, $price, $image]
            );
        }

        return redirect($response, '/admin/products');
    }
}
