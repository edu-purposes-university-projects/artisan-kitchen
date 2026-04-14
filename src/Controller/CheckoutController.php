<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CheckoutService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CheckoutController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $user = current_user();
        $username = isset($user['username']) ? (string) $user['username'] : '';

        $service = new CheckoutService();
        $defaults = $service->latestCustomerDefaults($username);

        $cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $summary = $service->summarizeCart($cartItems);

        return render_template($response, 'checkout', [
            'defaultName' => $defaults['name'],
            'defaultPhone' => $defaults['phone'],
            'defaultAddress' => $defaults['address'],
            'orderItems' => $summary['lines'],
            'totalPrice' => $summary['total'],
            'cartEmpty' => $summary['empty'],
        ]);
    }
}
