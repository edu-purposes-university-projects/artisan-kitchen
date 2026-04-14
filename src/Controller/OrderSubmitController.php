<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CheckoutService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OrderSubmitController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if ($request->getMethod() !== 'POST') {
            return redirect($response, '/checkout');
        }

        $parsed = $request->getParsedBody();
        $customerName = isset($parsed['customer_name']) ? trim((string) $parsed['customer_name']) : '';
        $customerPhone = isset($parsed['customer_phone']) ? trim((string) $parsed['customer_phone']) : '';
        $customerAddress = isset($parsed['customer_address']) ? trim((string) $parsed['customer_address']) : '';
        $paymentMethod = isset($parsed['payment_method']) ? trim((string) $parsed['payment_method']) : 'door';

        if ($customerName === '' || $customerPhone === '' || $customerAddress === '') {
            if (!empty($_SESSION['pending_order'])) {
                $pending = $_SESSION['pending_order'];
                $customerName = $customerName !== '' ? $customerName : (string) ($pending['customer_name'] ?? '');
                $customerPhone = $customerPhone !== '' ? $customerPhone : (string) ($pending['customer_phone'] ?? '');
                $customerAddress = $customerAddress !== '' ? $customerAddress : (string) ($pending['customer_address'] ?? '');
            }
        }

        if ($customerName === '' || $customerPhone === '' || $customerAddress === '') {
            return redirect($response, '/checkout');
        }

        $cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($cartItems === []) {
            return redirect($response, '/menu');
        }

        $user = current_user();
        $customerUsername = isset($user['username']) ? (string) $user['username'] : null;

        $service = new CheckoutService();
        $result = $service->placeOrder(
            $cartItems,
            $customerName,
            $customerPhone,
            $customerAddress,
            $customerUsername,
            $paymentMethod
        );

        if (!$result['ok']) {
            return redirect($response, '/checkout');
        }

        unset($_SESSION['cart'], $_SESSION['pending_order']);
        $_SESSION['last_payment_method'] = $paymentMethod;
        $_SESSION['last_order_phone'] = $customerPhone;

        return redirect($response, '/thank-you');
    }
}
