<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UsersPageController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $users = [];
        $ordersByUser = [];
        $usersTableMissing = false;

        $db = Database::connection();
        if ($db) {
            if (!$this->publicSchemaTableExists($db, 'users')) {
                $usersTableMissing = true;
            } else {
                $sql = '
                    SELECT u.id, u.username, u.role, u.created_at,
                           COUNT(o.order_id)::int AS order_count
                    FROM users u
                    LEFT JOIN orders o ON o.customer_username = u.username
                    GROUP BY u.id, u.username, u.role, u.created_at
                    ORDER BY u.username ASC
                ';
                $result = pg_query($db, $sql);
                if ($result) {
                    while ($row = pg_fetch_assoc($result)) {
                        $users[] = $row;
                        $username = (string) $row['username'];
                        $ordersByUser[$username] = $this->ordersWithItemsForUser($db, $username);
                    }
                }
            }
        }

        $json = json_encode(
            $ordersByUser,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );

        return render_template($response, 'admin/users', [
            'users' => $users,
            'ordersByUserJson' => $json,
            'usersTableMissing' => $usersTableMissing,
        ]);
    }

    /** @param resource $db */
    private function publicSchemaTableExists($db, string $table): bool
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            return false;
        }

        $result = pg_query_params(
            $db,
            'SELECT EXISTS (
                SELECT 1 FROM information_schema.tables
                WHERE table_schema = $1 AND table_name = $2
            ) AS ok',
            ['public', $table]
        );

        if (!$result) {
            return false;
        }

        $row = pg_fetch_assoc($result);
        $ok = $row['ok'] ?? null;

        return $ok === 't' || $ok === true || $ok === '1' || $ok === 1;
    }

    /**
     * @return list<array{order_id: int, total_price: float, status: string, created_at: string, payment_method: string, items: list<array{product_name: string, quantity: int, price: float}>}>
     */
    private function ordersWithItemsForUser($db, string $username): array
    {
        $sql = '
            SELECT o.order_id, o.total_price, o.status, o.created_at::text AS created_at, o.payment_method,
                   oi.quantity, oi.price_at_time_of_purchase,
                   p.name AS product_name
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.id = oi.product_id
            WHERE o.customer_username = $1
            ORDER BY o.created_at DESC, oi.item_id ASC
        ';
        $result = pg_query_params($db, $sql, [$username]);
        if (!$result || pg_num_rows($result) === 0) {
            return [];
        }

        $byOrder = [];
        while ($row = pg_fetch_assoc($result)) {
            $oid = (int) $row['order_id'];
            if (!isset($byOrder[$oid])) {
                $byOrder[$oid] = [
                    'order_id' => $oid,
                    'total_price' => (float) $row['total_price'],
                    'status' => (string) $row['status'],
                    'created_at' => (string) $row['created_at'],
                    'payment_method' => (string) $row['payment_method'],
                    'items' => [],
                ];
            }
            $byOrder[$oid]['items'][] = [
                'product_name' => (string) $row['product_name'],
                'quantity' => (int) $row['quantity'],
                'price' => (float) $row['price_at_time_of_purchase'],
            ];
        }

        return array_values($byOrder);
    }
}
