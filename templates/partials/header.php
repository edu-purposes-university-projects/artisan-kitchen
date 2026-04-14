<?php
$cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartCount = count($cartItems);
$activeNav = $activeNav ?? '';
$currentUser = function_exists('current_user') ? current_user() : null;
$displayName = isset($currentUser['username']) ? (string) $currentUser['username'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars((string) $pageTitle) . ' - ' : ''; ?>BALTACI Artisan Kitchen</title>
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
                    <a href="/" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo $activeNav === 'home' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        Home
                    </a>
                    <a href="/menu" class="text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo $activeNav === 'menu' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        Our Menu
                    </a>
                    <a href="/my-orders" class="hidden md:inline text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200 <?php echo $activeNav === 'my_orders' ? 'text-amber-600 border-b-2 border-amber-600' : ''; ?>">
                        My Orders
                    </a>

                    <?php if ($currentUser !== null && $displayName !== ''): ?>
                        <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                            <span class="text-sm font-medium text-gray-700 max-w-[5.5rem] sm:max-w-[9rem] truncate" title="<?php echo htmlspecialchars($displayName); ?>">
                                <?php echo htmlspecialchars($displayName); ?>
                            </span>
                            <a
                                href="/logout"
                                class="flex shrink-0 items-center justify-center rounded-full p-1.5 text-gray-600 hover:text-amber-600 hover:bg-amber-50 transition-colors duration-200"
                                title="Log out"
                                aria-label="Log out"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="text-sm text-gray-700 hover:text-amber-600 font-medium transition-colors duration-200">
                            Login
                        </a>
                    <?php endif; ?>

                    <div class="relative">
                        <button
                            type="button"
                            onclick="const p = document.getElementById('basket-popover'); if (p) { p.classList.toggle('hidden'); }"
                            class="relative flex items-center space-x-2 text-gray-700 hover:text-amber-600 transition-colors duration-200"
                            aria-label="View basket"
                        >
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
                                    <a href="/checkout"
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
