<?php
/** @var list<array<string, string>> $users */
/** @var string $ordersByUserJson */
/** @var bool $usersTableMissing */
$users = $users ?? [];
$ordersByUserJson = $ordersByUserJson ?? '{}';
$usersTableMissing = $usersTableMissing ?? false;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users | BALTACI Artisan Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="max-w-6xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-2">
                <h1 class="text-2xl font-bold text-amber-600 cursor-pointer" onclick="window.location.href = '/';">BALTACI Artisan Kitchen - Admin</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                    <a href="/admin/products" class="text-gray-600 hover:text-amber-600 font-medium">Products</a>
                    <span class="hidden sm:inline text-gray-400">|</span>
                    <a href="/admin/orders" class="text-gray-600 hover:text-amber-600 font-medium">Orders</a>
                    <span class="hidden sm:inline text-gray-400">|</span>
                    <span class="text-gray-500 font-medium">Users</span>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
                <?php if ($usersTableMissing): ?>
                    <div class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-950">
                        <p class="font-semibold mb-1">The <code class="bg-amber-100 px-1 rounded">users</code> table is not in your database yet.</p>
                        <p class="text-amber-900 mb-2"><strong>Easiest:</strong> Postgres açıkken proje kökünde terminalde çalıştırın:</p>
                        <pre class="bg-white border border-amber-200 rounded p-3 text-xs overflow-x-auto mb-3">cd "/path/to/BALTACI Artisan Kitchen"
php scripts/migrate_users.php</pre>
                        <p class="text-amber-900 mb-2"><strong>Alternatif — psql:</strong></p>
                        <pre class="bg-white border border-amber-200 rounded p-3 text-xs overflow-x-auto mb-2">psql -h localhost -U baltaci_user -d baltaci_kitchen -f docker/migrate_users.sql</pre>
                        <p class="text-xs text-amber-800">Şifre: <code class="bg-amber-100 px-1 rounded">baltaci_password</code> (docker-compose ile aynı). <strong>pgAdmin:</strong> <code class="bg-amber-100 px-1 rounded">docker/migrate_users.sql</code> içeriğini sorgu penceresinde çalıştırın. Sonra bu sayfayı yenileyin.</p>
                    </div>
                <?php endif; ?>

                <section class="bg-white rounded-lg shadow-md p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Users</h2>
                    <p class="text-sm text-gray-600 mb-6">Click the eye icon to view all orders placed by that user (customer accounts).</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member since</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($users !== []): ?>
                                    <?php foreach ($users as $row): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-500"><?php echo htmlspecialchars((string) $row['id']); ?></td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900"><?php echo htmlspecialchars((string) $row['username']); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-700"><?php echo htmlspecialchars((string) $row['role']); ?></td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700"><?php echo htmlspecialchars((string) $row['order_count']); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-500"><?php echo htmlspecialchars((string) $row['created_at']); ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center justify-center p-2 rounded-md text-amber-700 hover:bg-amber-50 border border-transparent hover:border-amber-200"
                                                    title="View orders"
                                                    aria-label="View orders for <?php echo htmlspecialchars((string) $row['username']); ?>"
                                                    data-view-orders="<?php echo htmlspecialchars((string) $row['username'], ENT_QUOTES, 'UTF-8'); ?>"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php elseif ($usersTableMissing): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                            Table not created yet — follow the steps in the yellow box above.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No users in the database.
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

    <!-- Modal -->
    <div id="orders-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4" aria-modal="true" role="dialog">
        <div id="orders-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                <h3 id="orders-modal-title" class="text-lg font-semibold text-gray-800">Orders</h3>
                <button type="button" id="orders-modal-close" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-800" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="orders-modal-body" class="overflow-y-auto p-4 text-sm text-gray-700"></div>
        </div>
    </div>

    <script type="application/json" id="user-orders-data"><?php echo $ordersByUserJson; ?></script>
    <script>
        (function () {
            const dataEl = document.getElementById('user-orders-data');
            let userOrders = {};
            try {
                userOrders = JSON.parse(dataEl.textContent || '{}');
            } catch (e) {
                userOrders = {};
            }

            const modal = document.getElementById('orders-modal');
            const backdrop = document.getElementById('orders-modal-backdrop');
            const body = document.getElementById('orders-modal-body');
            const title = document.getElementById('orders-modal-title');
            const closeBtn = document.getElementById('orders-modal-close');

            function escapeHtml(s) {
                const d = document.createElement('div');
                d.textContent = s;
                return d.innerHTML;
            }

            function formatMoney(n) {
                return Number(n).toFixed(2) + ' TL';
            }

            function openModal(username) {
                const orders = userOrders[username] || [];
                title.textContent = 'Orders — ' + username;

                if (!orders.length) {
                    body.innerHTML = '<p class="text-gray-500 text-center py-8">This user has no orders yet (or orders were placed without a logged-in username).</p>';
                } else {
                    let html = '<div class="space-y-6">';
                    orders.forEach(function (o) {
                        html += '<div class="border border-gray-200 rounded-lg overflow-hidden">';
                        html += '<div class="bg-gray-50 px-3 py-2 flex flex-wrap gap-2 justify-between items-center text-xs sm:text-sm">';
                        html += '<span class="font-mono font-semibold text-gray-800">#' + escapeHtml(String(o.order_id)) + '</span>';
                        html += '<span class="text-gray-600">' + escapeHtml(o.created_at || '') + '</span>';
                        html += '<span class="font-semibold text-amber-700">' + formatMoney(o.total_price) + '</span>';
                        html += '<span class="px-2 py-0.5 rounded-full bg-gray-200 text-gray-800">' + escapeHtml(o.status || '') + '</span>';
                        html += '<span class="text-gray-500">' + escapeHtml(o.payment_method === 'online' ? 'Online' : 'Door') + '</span>';
                        html += '</div>';
                        html += '<table class="min-w-full text-xs sm:text-sm"><thead><tr class="border-t border-gray-100 bg-white">';
                        html += '<th class="text-left px-3 py-2 text-gray-500">Product</th>';
                        html += '<th class="text-right px-3 py-2 text-gray-500">Qty</th>';
                        html += '<th class="text-right px-3 py-2 text-gray-500">Price</th>';
                        html += '</tr></thead><tbody>';
                        (o.items || []).forEach(function (it) {
                            html += '<tr class="border-t border-gray-100">';
                            html += '<td class="px-3 py-2">' + escapeHtml(it.product_name || '') + '</td>';
                            html += '<td class="px-3 py-2 text-right">' + escapeHtml(String(it.quantity)) + '</td>';
                            html += '<td class="px-3 py-2 text-right">' + formatMoney(it.price) + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table></div>';
                    });
                    html += '</div>';
                    body.innerHTML = html;
                }

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            document.querySelectorAll('[data-view-orders]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn.getAttribute('data-view-orders') || '');
                });
            });

            closeBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        })();
    </script>
</body>
</html>
