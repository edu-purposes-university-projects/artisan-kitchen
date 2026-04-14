<?php
$pageTitle = 'Thank You';
$activeNav = '';
include __DIR__ . '/partials/header.php';
$paymentMethod = $paymentMethod ?? 'door';
?>

<section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-20 md:py-28">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">
            Thank you for your order!
        </h1>
        <p class="text-xl md:text-2xl text-amber-100 max-w-2xl mx-auto">
            <?php if ($paymentMethod === 'online'): ?>
                Your online payment has been received. We will start preparing your order shortly.
            <?php else: ?>
                We will call you shortly to confirm your order and you can pay safely at your door.
            <?php endif; ?>
        </p>
        <div class="mt-10">
            <a href="/menu"
               class="inline-block bg-white text-amber-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-amber-50 transition-colors duration-200 shadow-lg">
                Back to Menu
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
