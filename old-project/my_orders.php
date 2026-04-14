<?php
session_start();

require_once(__DIR__ . '/auth/jwt.php');
require_role('customer');

include __DIR__ . '/config/db_connect.php';

$pageTitle = 'My Orders';

$user = current_user();
$customerUsername = isset($user['username']) ? $user['username'] : null;

$ordersResult = null;

if ($customerUsername !== null) {
    $query  = "SELECT order_id, total_price, payment_method, status, created_at FROM orders WHERE customer_username = $1 ORDER BY created_at DESC";
    $params = [$customerUsername];
    $ordersResult = pg_query_params($db, $query, $params);
}

include __DIR__ . '/header.php';
?>

<section class="bg-gray-50 py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-4xl space-y-8">
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 text-center">
                My Orders
            </h1>
            <p class="text-sm text-gray-600 mb-4 text-center">
                These are the orders placed while you are logged in as <span class="font-semibold"><?php echo htmlspecialchars($customerUsername ?? 'customer'); ?></span>.
            </p>
        </div>

        <?php if ($customerUsername !== null): ?>
            <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    Your recent orders
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
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
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                                <?php echo htmlspecialchars($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-right text-gray-500">
                                            <?php echo htmlspecialchars($order['created_at']); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                        No orders found for this phone number.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>


