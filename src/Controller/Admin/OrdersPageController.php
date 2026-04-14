<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OrdersPageController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $orders = [];
        $db = Database::connection();
        if ($db) {
            $sql = '
                SELECT
                    o.order_id,
                    o.customer_name,
                    o.customer_phone,
                    o.customer_address,
                    o.total_price,
                    o.payment_method,
                    o.status,
                    o.created_at,
                    COUNT(oi.item_id) AS item_count
                FROM orders o
                LEFT JOIN order_items oi ON oi.order_id = o.order_id
                GROUP BY o.order_id
                ORDER BY o.created_at DESC
            ';
            $result = pg_query($db, $sql);
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    $orders[] = $row;
                }
            }
        }

        return render_template($response, 'admin/orders', [
            'orders' => $orders,
        ]);
    }
}
