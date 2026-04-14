<?php

declare(strict_types=1);

namespace App\Service;

use App\Database;

final class CheckoutService
{
    /**
     * @param list<int|string> $cartItems
     * @return array{lines: list<array{id:int,name:string,price:float,quantity:int,line_total:float}>, total: float, empty: bool}
     */
    public function summarizeCart(array $cartItems): array
    {
        $lines = [];
        $total = 0.0;

        if ($cartItems === []) {
            return ['lines' => [], 'total' => 0.0, 'empty' => true];
        }

        $db = Database::connection();
        if (!$db) {
            return ['lines' => [], 'total' => 0.0, 'empty' => true];
        }

        $counts = array_count_values(array_map('intval', $cartItems));
        $productIds = array_keys($counts);
        if ($productIds === []) {
            return ['lines' => [], 'total' => 0.0, 'empty' => true];
        }

        $placeholders = [];
        $params = [];
        foreach ($productIds as $index => $id) {
            $placeholders[] = '$' . ($index + 1);
            $params[] = $id;
        }

        $query = 'SELECT id, name, price FROM products WHERE id IN (' . implode(', ', $placeholders) . ')';
        $result = pg_query_params($db, $query, $params);

        if (!$result || pg_num_rows($result) === 0) {
            return ['lines' => [], 'total' => 0.0, 'empty' => true];
        }

        while ($row = pg_fetch_assoc($result)) {
            $id = (int) $row['id'];
            $quantity = $counts[$id] ?? 0;
            if ($quantity < 1) {
                continue;
            }
            $price = (float) $row['price'];
            $lineTotal = $price * $quantity;
            $lines[] = [
                'id' => $id,
                'name' => (string) $row['name'],
                'price' => $price,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
            $total += $lineTotal;
        }

        return ['lines' => $lines, 'total' => $total, 'empty' => $lines === []];
    }

    /**
     * @return array{name: string, phone: string, address: string}
     */
    public function latestCustomerDefaults(string $username): array
    {
        $defaults = ['name' => '', 'phone' => '', 'address' => ''];
        $db = Database::connection();
        if (!$db) {
            return $defaults;
        }

        $sql = '
            SELECT customer_name, customer_phone, customer_address
            FROM orders
            WHERE customer_username = $1
            ORDER BY created_at DESC
            LIMIT 1
        ';
        $result = pg_query_params($db, $sql, [$username]);
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $defaults['name'] = (string) ($row['customer_name'] ?? '');
            $defaults['phone'] = (string) ($row['customer_phone'] ?? '');
            $defaults['address'] = (string) ($row['customer_address'] ?? '');
        }

        return $defaults;
    }

    /**
     * @param list<int|string> $cartItems
     * @return array{ok: bool, order_id?: int}
     */
    public function placeOrder(
        array $cartItems,
        string $customerName,
        string $customerPhone,
        string $customerAddress,
        ?string $customerUsername,
        string $paymentMethod
    ): array {
        $db = Database::connection();
        if (!$db) {
            return ['ok' => false];
        }

        $counts = array_count_values(array_map('intval', $cartItems));
        $productIds = array_keys($counts);
        if ($productIds === []) {
            return ['ok' => false];
        }

        $placeholders = [];
        $params = [];
        foreach ($productIds as $index => $id) {
            $placeholders[] = '$' . ($index + 1);
            $params[] = $id;
        }

        $query = 'SELECT id, name, price FROM products WHERE id IN (' . implode(', ', $placeholders) . ')';
        $result = pg_query_params($db, $query, $params);

        if (!$result || pg_num_rows($result) === 0) {
            return ['ok' => false];
        }

        $orderItems = [];
        $totalPrice = 0.0;

        while ($row = pg_fetch_assoc($result)) {
            $id = (int) $row['id'];
            if (!isset($counts[$id])) {
                continue;
            }
            $quantity = $counts[$id];
            $price = (float) $row['price'];
            $lineTotal = $price * $quantity;
            $orderItems[] = [
                'product_id' => $id,
                'quantity' => $quantity,
                'price' => $price,
            ];
            $totalPrice += $lineTotal;
        }

        if ($orderItems === []) {
            return ['ok' => false];
        }

        $orderQuery = 'INSERT INTO orders (customer_name, customer_phone, customer_username, customer_address, total_price, payment_method, status) VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING order_id';
        $orderParams = [$customerName, $customerPhone, $customerUsername, $customerAddress, $totalPrice, $paymentMethod, 'Pending'];
        $orderResult = pg_query_params($db, $orderQuery, $orderParams);

        if (!$orderResult) {
            return ['ok' => false];
        }

        $orderRow = pg_fetch_assoc($orderResult);
        $orderId = isset($orderRow['order_id']) ? (int) $orderRow['order_id'] : 0;

        if ($orderId <= 0) {
            return ['ok' => false];
        }

        foreach ($orderItems as $item) {
            $itemQuery = 'INSERT INTO order_items (order_id, product_id, quantity, price_at_time_of_purchase) VALUES ($1, $2, $3, $4)';
            pg_query_params($db, $itemQuery, [
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price'],
            ]);
        }

        return ['ok' => true, 'order_id' => $orderId];
    }
}
