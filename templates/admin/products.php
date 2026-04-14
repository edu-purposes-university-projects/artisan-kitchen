<?php
/** @var list<array<string, string>> $products */
$products = $products ?? [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products | BALTACI Artisan Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-amber-600 cursor-pointer" onclick="window.location.href = '/';">BALTACI Artisan Kitchen - Admin</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                    <span class="text-gray-500 font-medium">Products</span>
                    <span class="hidden sm:inline text-gray-400">|</span>
                    <a href="/admin/orders" class="text-gray-600 hover:text-amber-600 font-medium">Orders</a>
                    <span class="hidden sm:inline text-gray-400">|</span>
                    <a href="/admin/users" class="text-gray-600 hover:text-amber-600 font-medium">Users</a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 py-8 space-y-10">
                <section class="bg-white rounded-lg shadow-md p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">Add New Product</h2>
                    <form action="/admin/products" method="POST" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (e.g. 150.00)</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image URL (optional)</label>
                                <input type="text" id="image_url" name="image_url"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Add Product
                            </button>
                        </div>
                    </form>
                </section>

                <section class="bg-white rounded-lg shadow-md p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">Current Products</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($products !== []): ?>
                                    <?php foreach ($products as $row): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                <?php echo htmlspecialchars((string) $row['id']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars((string) $row['name']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <?php echo htmlspecialchars((string) $row['price']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="/admin/products/delete/<?php echo urlencode((string) $row['id']); ?>"
                                                   class="inline-flex items-center px-3 py-1.5 border border-red-600 text-xs font-medium rounded-md text-red-600 hover:bg-red-50"
                                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                            No products found. Add your first product above.
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
