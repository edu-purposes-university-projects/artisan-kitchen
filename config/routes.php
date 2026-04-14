<?php

declare(strict_types=1);

use App\Controller\Admin\CreateProductController;
use App\Controller\Admin\DeleteProductController;
use App\Controller\Admin\OrdersPageController;
use App\Controller\Admin\ProductsPageController;
use App\Controller\Admin\UpdateOrderStatusController;
use App\Controller\Admin\UsersPageController;
use App\Controller\CartAddController;
use App\Controller\CheckoutController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\MenuController;
use App\Controller\MyOrdersController;
use App\Controller\OrderSubmitController;
use App\Controller\PaymentController;
use App\Controller\ThankYouController;
use App\Middleware\RequireAdminMiddleware;
use App\Middleware\RequireCustomerMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $app->get('/', HomeController::class);
    $app->get('/menu', MenuController::class);
    $app->map(['GET', 'POST'], '/login', LoginController::class);
    $app->get('/logout', LogoutController::class);

    $app->group('', function (RouteCollectorProxy $group): void {
        $group->post('/cart/add', CartAddController::class);
        $group->get('/checkout', CheckoutController::class);
        $group->post('/checkout/submit', OrderSubmitController::class);
        $group->map(['GET', 'POST'], '/checkout/payment', PaymentController::class);
        $group->get('/thank-you', ThankYouController::class);
        $group->get('/my-orders', MyOrdersController::class);
    })->add(new RequireCustomerMiddleware());

    $app->group('', function (RouteCollectorProxy $group): void {
        $group->get('/admin/products', ProductsPageController::class);
        $group->post('/admin/products', CreateProductController::class);
        $group->get('/admin/products/delete/{id}', DeleteProductController::class);
        $group->get('/admin/orders', OrdersPageController::class);
        $group->post('/admin/orders/status', UpdateOrderStatusController::class);
        $group->get('/admin/users', UsersPageController::class);
    })->add(new RequireAdminMiddleware());
};
