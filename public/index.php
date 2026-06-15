<?php

declare(strict_types=1);

use App\Middleware\SessionMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../config/env.php';
load_env(dirname(__DIR__));

require_once __DIR__ . '/../auth/jwt.php';

$app = AppFactory::create();

$app->setBasePath('');

$app->add(new SessionMiddleware());
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();
