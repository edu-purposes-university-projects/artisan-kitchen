<?php
$pageTitle = 'Our Menu';
$activeNav = 'menu';
include __DIR__ . '/partials/header.php';
/** @var array<int, array<string, string>> $menuItems */
$menuItems = $menuItems ?? [];
?>

<section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12 md:py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Menu</h1>
        <p class="text-xl text-amber-100">Discover our artisan selection of premium dishes</p>
    </div>
</section>

<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <?php if (!empty($menuItems)): ?>
                <?php foreach ($menuItems as $item): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <div class="relative h-64 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'https://placehold.co/600x400?text=No+Image'); ?>"
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-2xl font-bold text-amber-600">
                                    <?php echo htmlspecialchars(number_format((float) $item['price'], 2)); ?> TL
                                </span>
                                <form action="/cart/add" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <button type="submit" name="add_to_cart"
                                            class="bg-amber-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-amber-700 transition-colors duration-200">
                                        Add to Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500">No products available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto bg-amber-50 p-8 rounded-lg border-2 border-amber-200">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                Ready to Order?
            </h2>
            <p class="text-gray-600 mb-6 text-lg">
                Call us at <strong class="text-amber-600 text-xl">555-123-456</strong> to place your order.
            </p>
            <p class="text-gray-500">
                We're here to help you enjoy our artisan meals!
            </p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
