<?php
require_once(__DIR__ . '/auth/jwt.php');

$pageTitle = 'Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Simple hardcoded users for demo.
    // In a real app, move this to a users table.
    $users = [
        'admin' => [
            'password' => 'admin123',
            'role' => 'admin',
        ],
        'customer' => [
            'password' => 'customer123',
            'role' => 'customer',
        ],
    ];

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $role = $users[$username]['role'];

        $token = create_jwt([
            'username' => $username,
            'role' => $role,
        ], 60 * 60 * 4); // 4 hours

        // Secure cookie settings: adjust 'secure' flag if not using HTTPS.
        setcookie(
            'auth_token',
            $token,
            [
                'expires' => time() + 60 * 60 * 4,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );

        if ($role === 'admin') {
            header('Location: /admin/products.php');
        } else {
            header('Location: /menu.php');
        }
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}

include(__DIR__ . '/header.php');
?>

<section class="min-h-[70vh] flex items-center justify-center bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">
                Login
            </h1>

            <?php if ($error): ?>
                <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    >
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    >
                </div>

                <div class="text-xs text-gray-500 mb-2">
                    Demo credentials:
                    <br>Admin → <span class="font-mono">admin / admin123</span>
                    <br>Customer → <span class="font-mono">customer / customer123</span>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
                >
                    Sign In
                </button>
            </form>
        </div>
    </div>
</section>

<?php include(__DIR__ . '/footer.php'); ?>


