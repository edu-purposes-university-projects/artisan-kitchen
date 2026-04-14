<?php
// Simple JWT utilities for authentication
// NOTE: Replace the secret key with a strong, private value in production.

$JWT_SECRET = 'CHANGE_ME_TO_A_SECURE_RANDOM_SECRET';

function jwt_base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function jwt_base64url_decode(string $data): string
{
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function create_jwt(array $payload, int $expiresInSeconds = 3600): string
{
    global $JWT_SECRET;

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $issuedAt = time();
    $payload['iat'] = $issuedAt;
    $payload['exp'] = $issuedAt + $expiresInSeconds;

    $headerEncoded = jwt_base64url_encode(json_encode($header));
    $payloadEncoded = jwt_base64url_encode(json_encode($payload));

    $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $JWT_SECRET, true);
    $signatureEncoded = jwt_base64url_encode($signature);

    return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
}

function verify_jwt(string $token): ?array
{
    global $JWT_SECRET;

    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }

    [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

    $signature = jwt_base64url_encode(
        hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $JWT_SECRET, true)
    );

    if (!hash_equals($signature, $signatureEncoded)) {
        return null;
    }

    $payloadJson = jwt_base64url_decode($payloadEncoded);
    $payload = json_decode($payloadJson, true);

    if (!is_array($payload)) {
        return null;
    }

    if (isset($payload['exp']) && time() > (int) $payload['exp']) {
        return null;
    }

    return $payload;
}

function current_user(): ?array
{
    if (empty($_COOKIE['auth_token'])) {
        return null;
    }

    $payload = verify_jwt($_COOKIE['auth_token']);
    return $payload ?: null;
}

function require_role(?string $requiredRole = null): array
{
    $user = current_user();

    if (!$user) {
        // No valid token, redirect to login (implement this page separately)
        header('Location: /login.php');
        exit;
    }

    if ($requiredRole !== null) {
        if (!isset($user['role']) || $user['role'] !== $requiredRole) {
            http_response_code(403);

            // Show a friendly, styled message instead of plain text.
            $rootDir = dirname(__DIR__);
            $headerPath = $rootDir . '/header.php';
            $footerPath = $rootDir . '/footer.php';

            if (file_exists($headerPath)) {
                $pageTitle = 'Access Denied';
                include $headerPath;
            } else {
                echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Access Denied</title>';
                echo '<script src="https://cdn.tailwindcss.com"></script></head><body class="bg-gray-50">';
            }
            ?>
            <section class="min-h-[70vh] flex items-center justify-center bg-gray-50 py-12">
                <div class="container mx-auto px-4 max-w-lg">
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                            You need to log in to continue
                        </h1>
                        <p class="text-gray-600 mb-6">
                            Your account does not have permission to view this page, or your session has expired.
                        </p>
                        <a href="/login.php"
                           class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Go to Login
                        </a>
                    </div>
                </div>
            </section>
            <?php
            if (file_exists($footerPath)) {
                include $footerPath;
            } else {
                echo '</body></html>';
            }

            exit;
        }
    }

    return $user;
}


