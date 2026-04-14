<?php
$pageTitle = "Home";
include __DIR__ . '/header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-20 md:py-32">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-6xl font-bold mb-6">
            Artisan Tastes, Delivered to Your Door
        </h2>
        <p class="text-xl md:text-2xl mb-8 text-amber-100">
            Premium quality meals crafted with passion in Sivrihisar
        </p>
        <a href="menu.php" class="inline-block bg-white text-amber-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-amber-50 transition-colors duration-200 shadow-lg">
            View Our Menu
        </a>
    </div>
</section>

<!-- About Us Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">
                About Us
            </h2>
            <div class="prose prose-lg mx-auto text-gray-600 text-center">
                <p class="text-lg leading-relaxed">
                    Welcome to <strong>BALTACI Artisan Kitchen</strong>, a boutique, high-quality kitchen located in the heart of Sivrihisar, Eskişehir. 
                    We specialize in crafting exceptional meals with the finest ingredients and traditional techniques. 
                    As an order-only kitchen, we focus entirely on delivering premium quality food directly to your door. 
                    Our commitment is to bring you artisan tastes that reflect our passion for culinary excellence, 
                    ensuring every dish is prepared with care and attention to detail.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How to Order Section -->
<section class="py-16 md:py-24 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-12 text-center">
            How to Order
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Step 1 -->
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <div class="bg-amber-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                    1
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Browse Our Menu</h3>
                <p class="text-gray-600">
                    Explore our carefully curated selection of artisan dishes, each crafted with premium ingredients.
                </p>
                <a href="menu.php" class="inline-block mt-4 text-amber-600 font-semibold hover:text-amber-700">
                    View Menu →
                </a>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <div class="bg-amber-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                    2
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Call to Order</h3>
                <p class="text-gray-600">
                    Give us a call at <strong>555-123-456</strong> to place your order. Our team is ready to assist you.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow duration-200">
                <div class="bg-amber-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                    3
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Enjoy Your Meal</h3>
                <p class="text-gray-600">
                    Sit back and relax while we prepare your order. We'll deliver it fresh and ready to enjoy.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>

