<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PaymentController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if ($request->getMethod() === 'POST') {
            $parsed = $request->getParsedBody();
            $name = isset($parsed['customer_name']) ? trim((string) $parsed['customer_name']) : '';
            $phone = isset($parsed['customer_phone']) ? trim((string) $parsed['customer_phone']) : '';
            $address = isset($parsed['customer_address']) ? trim((string) $parsed['customer_address']) : '';

            if ($name === '' || $phone === '' || $address === '') {
                return redirect($response, '/checkout');
            }

            $_SESSION['pending_order'] = [
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_address' => $address,
            ];
        } elseif (empty($_SESSION['pending_order'])) {
            return redirect($response, '/checkout');
        }

        return render_template($response, 'payment');
    }
}
