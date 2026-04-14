<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ProductsPageController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $products = [];
        $db = Database::connection();
        if ($db) {
            $result = pg_query($db, 'SELECT id, name, price FROM products ORDER BY id DESC');
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $products[] = $row;
                }
            }
        }

        return render_template($response, 'admin/products', [
            'products' => $products,
        ]);
    }
}
