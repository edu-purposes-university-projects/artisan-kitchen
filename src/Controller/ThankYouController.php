<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ThankYouController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $paymentMethod = isset($_SESSION['last_payment_method']) ? (string) $_SESSION['last_payment_method'] : 'door';

        return render_template($response, 'thank_you', [
            'paymentMethod' => $paymentMethod,
        ]);
    }
}
