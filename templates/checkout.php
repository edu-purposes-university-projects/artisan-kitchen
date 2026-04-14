<?php
$pageTitle = 'Checkout';
$activeNav = '';
include __DIR__ . '/partials/header.php';
?>

<section class="bg-gray-50 py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-8 text-center">Your Order</h1>

        <?php if ($cartEmpty): ?>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 mb-4">Your cart is empty.</p>
                <a href="/menu" class="inline-block mt-2 text-amber-600 font-semibold hover:text-amber-700">
                    Go back to menu
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Cart Summary</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-700">
                                        <?php echo htmlspecialchars((string) $item['quantity']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-700">
                                        <?php echo htmlspecialchars(number_format($item['price'], 2)); ?> TL
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium">
                                        <?php echo htmlspecialchars(number_format($item['line_total'], 2)); ?> TL
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-800">
                                    Total
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-bold text-amber-600">
                                    <?php echo htmlspecialchars(number_format($totalPrice, 2)); ?> TL
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Your Details</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input type="text" id="customer_name" name="customer_name" required
                               value="<?php echo htmlspecialchars($defaultName); ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Your Phone Number</label>
                        <input type="text" id="customer_phone" name="customer_phone" required
                               value="<?php echo htmlspecialchars($defaultPhone); ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea id="customer_address" name="customer_address" rows="3" required
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                  placeholder="Street, building, floor, apartment, district"><?php echo htmlspecialchars($defaultAddress); ?></textarea>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-700 mb-2">Payment Method</span>
                        <div class="space-y-1">
                            <label class="inline-flex items-center space-x-2">
                                <input type="radio" name="payment_method" value="door" checked
                                       class="text-amber-600 border-gray-300 focus:ring-amber-500"
                                       onclick="updateCheckoutButton('door')">
                                <span class="text-sm text-gray-700">Pay at the door (cash/card)</span>
                            </label>
                            <label class="inline-flex items-center space-x-2">
                                <input type="radio" name="payment_method" value="online"
                                       class="text-amber-600 border-gray-300 focus:ring-amber-500"
                                       onclick="updateCheckoutButton('online')">
                                <span class="text-sm text-gray-700">Online payment</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button id="checkout-main-button" type="submit" formaction="/checkout/submit"
                                class="w-full inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Place Order &amp; Pay at Door
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    function updateCheckoutButton(method) {
        const btn = document.getElementById('checkout-main-button');
        if (!btn) return;

        if (method === 'online') {
            btn.setAttribute('formaction', '/checkout/payment');
            btn.textContent = 'Continue to Online Payment';
        } else {
            btn.setAttribute('formaction', '/checkout/submit');
            btn.textContent = 'Place Order & Pay at Door';
        }
    }
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
