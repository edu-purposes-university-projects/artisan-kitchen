<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class MenuController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $menuItems = [];
        $db = Database::connection();
        if ($db) {
            $result = pg_query($db, 'SELECT id, name, description, price, image_url FROM products ORDER BY id DESC');
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $menuItems[] = $row;
                }
            }
        }

        return render_template($response, 'menu', ['menuItems' => $menuItems]);
    }
}
