<?php

declare(strict_types=1);


ini_set('display_errors', '1');
error_reporting(E_ALL);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

define('APP_ENV', $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'DEVELOPMENT');
$settings = (require __DIR__ . '/../config/settings.php')(APP_ENV);

// Set up dependencies
$containerBuilder = new ContainerBuilder();
if($settings['di_compilation_path']) {
    $containerBuilder->enableCompilation($settings['di_compilation_path']);
}
(require __DIR__ . '/../config/dependencies.php')($containerBuilder, $settings);

// Create app
AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();

// Assign matched route arguments to Request attributes for PSR-15 handlers
$app->getRouteCollector()->setDefaultInvocationStrategy(new RequestHandler(true));

// Register middleware
(require __DIR__ . '/../config/middleware.php')($app);

// Register routes
(require __DIR__ . '/../config/routes.php')($app);

// Run app
$app->run();