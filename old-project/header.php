<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart badge based on session cart
$cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartCount = count($cartItems);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>BALTACI Artisan Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-amber-600">
                        BALTACI Artisan Kitchen
                    </h1>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="index.php" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        Home
                    </a>
                    <a href="menu.php" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        Our Menu
                    </a>
                    <a href="my_orders.php" class="hidden md:inline text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'my_orders.php' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        My Orders
                    </a>

                    <!-- Basket button + popover -->
                    <div class="relative">
                        <button
                            type="button"
                            onclick="const p = document.getElementById('basket-popover'); if (p) { p.classList.toggle('hidden'); }"
                            class="relative flex items-center space-x-2 text-gray-700 hover:text-amber-600 transition-colors duration-200"
                            aria-label="View basket"
                        >
                            <!-- Simple cart icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 3h2l2 13h10l2-9H7" />
                                <circle cx="10" cy="19" r="1.2" />
                                <circle cx="17" cy="19" r="1.2" />
                            </svg>
                            <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 text-xs font-semibold rounded-full bg-amber-600 text-white">
                                <?php echo (int) $cartCount; ?>
                            </span>
                        </button>

                        <div
                            id="basket-popover"
                            class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                        >
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-800 mb-2">Your Basket</h3>
                                <?php if ($cartCount === 0): ?>
                                    <p class="text-xs text-gray-500">Your basket is empty.</p>
                                <?php else: ?>
                                    <p class="text-xs text-gray-600 mb-2">
                                        You have <span class="font-semibold"><?php echo (int) $cartCount; ?></span> item(s) in your basket.
                                    </p>
                                    <a href="checkout.php"
                                       class="mt-2 inline-flex w-full justify-center px-3 py-2 text-xs font-medium rounded-md bg-amber-600 text-white hover:bg-amber-700">
                                        View &amp; Checkout
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>


