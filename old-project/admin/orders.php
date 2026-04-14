<?php
include('../config/db_connect.php');
require_once('../auth/jwt.php');

// Only admins can see orders
require_role('admin');

// Fetch orders with basic aggregates
$ordersQuery = "
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
";
$ordersResult = pg_query($db, $ordersQuery);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders | BALTACI Artisan Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-amber-600" href="/">BALTACI Artisan Kitchen - Admin</h1>
                <div class="flex items-center space-x-4 text-sm">
                    <a href="products.php" class="text-gray-600 hover:text-amber-600 font-medium">
                        Manage Products
                    </a>
                    <span class="hidden md:inline text-gray-400">|</span>
                    <span class="text-gray-500">Orders Dashboard</span>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 py-8 space-y-8">
                <section class="bg-white rounded-lg shadow-md p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">Recent Orders</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($ordersResult && pg_num_rows($ordersResult) > 0): ?>
                                    <?php while ($order = pg_fetch_assoc($ordersResult)): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700 font-mono">
                                                #<?php echo htmlspecialchars($order['order_id']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                                <?php echo htmlspecialchars($order['customer_name']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($order['customer_phone']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                                                <div class="line-clamp-2">
                                                    <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700">
                                                <?php echo htmlspecialchars($order['item_count']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 font-semibold">
                                                <?php echo htmlspecialchars(number_format((float)$order['total_price'], 2)); ?> TL
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                                <?php if ($order['payment_method'] === 'online'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 font-medium">
                                                        Online
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-50 text-amber-700 font-medium">
                                                        Door
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                                <form action="update_order_status.php" method="POST" class="inline-flex items-center space-x-2">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <select name="status"
                                                            class="text-xs border border-gray-300 rounded-md px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                                        <?php
                                                        $statuses = ['Pending', 'Preparing', 'Shipping', 'Completed', 'Cancelled'];
                                                        foreach ($statuses as $status):
                                                            $selected = ($order['status'] === $status) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo htmlspecialchars($status); ?>" <?php echo $selected; ?>>
                                                                <?php echo htmlspecialchars($status); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 rounded-md bg-amber-600 text-white hover:bg-amber-700">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-right text-gray-500">
                                                <?php echo htmlspecialchars($order['created_at']); ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">
                                            No orders have been placed yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>

        <footer class="bg-gray-800 text-white mt-8">
            <div class="max-w-6xl mx-auto px-4 py-4 text-center text-sm text-gray-300">
                Admin Panel - BALTACI Artisan Kitchen
            </div>
        </footer>
    </div>
</body>
</html>


