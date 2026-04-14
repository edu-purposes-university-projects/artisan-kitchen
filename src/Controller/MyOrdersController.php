<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class MyOrdersController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $user = current_user();
        $customerUsername = isset($user['username']) ? (string) $user['username'] : null;

        $orders = [];
        $db = Database::connection();
        if ($db && $customerUsername !== null) {
            $sql = 'SELECT order_id, total_price, payment_method, status, created_at FROM orders WHERE customer_username = $1 ORDER BY created_at DESC';
            $result = pg_query_params($db, $sql, [$customerUsername]);
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $orders[] = $row;
                }
            }
        }

        return render_template($response, 'my_orders', [
            'customerUsername' => $customerUsername,
            'orders' => $orders,
        ]);
    }
}
