<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UpdateOrderStatusController
{
    private const ALLOWED = ['Pending', 'Preparing', 'Shipping', 'Completed', 'Cancelled'];

    public function __invoke(Request $request, Response $response): Response
    {
        if ($request->getMethod() !== 'POST') {
            return redirect($response, '/admin/orders');
        }

        $parsed = $request->getParsedBody();
        $orderId = isset($parsed['order_id']) ? (int) $parsed['order_id'] : 0;
        $status = isset($parsed['status']) ? trim((string) $parsed['status']) : '';

        $db = Database::connection();
        if ($db && $orderId > 0 && in_array($status, self::ALLOWED, true)) {
            pg_query_params($db, 'UPDATE orders SET status = $1 WHERE order_id = $2', [$status, $orderId]);
        }

        return redirect($response, '/admin/orders');
    }
}
