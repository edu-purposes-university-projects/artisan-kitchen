<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DeleteProductController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = isset($args['id']) ? (int) $args['id'] : 0;
        $db = Database::connection();

        if ($db && $id > 0) {
            pg_query_params($db, 'DELETE FROM products WHERE id = $1', [$id]);
        }

        return redirect($response, '/admin/products');
    }
}
