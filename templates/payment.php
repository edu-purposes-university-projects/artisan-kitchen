<?php
$pageTitle = 'Online Payment';
$activeNav = '';
include __DIR__ . '/partials/header.php';
?>

<section class="bg-gray-50 py-12 md:py-16">
    <div class="container mx-auto px-4 max-w-lg">
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 text-center">
                Online Payment
            </h1>
            <p class="text-sm text-gray-600 mb-6 text-center">
                This is a demo payment form. Card details are not actually processed.
            </p>

            <form action="/checkout/submit" method="POST" class="space-y-4">
                <input type="hidden" name="payment_method" value="online">

                <div>
                    <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Name on Card</label>
                    <input type="text" id="card_name" name="card_name" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                    <input type="text" id="card_number" name="card_number" required
                           placeholder="4242 4242 4242 4242"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="card_exp" class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                        <input type="text" id="card_exp" name="card_exp" required
                               placeholder="12/28"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                        <input type="text" id="card_cvc" name="card_cvc" required
                               placeholder="123"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Pay &amp; Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
