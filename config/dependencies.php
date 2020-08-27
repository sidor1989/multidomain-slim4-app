<?php

declare(strict_types=1);

use DI\ContainerBuilder;

use Slim\App;
use Slim\Factory\AppFactory;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use Slim\Views\Twig;

use \Illuminate\Database\Capsule\Manager;
use \Slim\Flash\Messages;

use Slim\Interfaces\CallableResolverInterface;
use Slim\CallableResolver;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RouteCollector;

return static function (ContainerBuilder $containerBuilder, array $settings) {
    $containerBuilder->addDefinitions([
        'settings' => $settings,

        App::class => function (ContainerInterface $c) {
            AppFactory::setContainer($c);
            $app = AppFactory::create();

            // Optional: Set the base path to run the app in a subdirectory.
            //$app->setBasePath('/slim4-tutorial');

            return $app;
        },


        ResponseFactoryInterface::class => function () {
            return AppFactory::determineResponseFactory();
        },
        CallableResolverInterface::class => function (ContainerInterface $c) {
            return new CallableResolver($c);
        },

        RouteCollectorInterface::class => function (ContainerInterface $c) {
            return new RouteCollector(
                $c->get(ResponseFactoryInterface::class),
                $c->get(CallableResolverInterface::class),
                $c
            );
        },

        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        Twig::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');
            $cache = $settings['templates.cache'];

            return Twig::create($settings['templates.dir'], ['cache' => empty($cache) ? false : $cache]);
        },

        // Service factory for the ORM
        Manager::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');
            $capsule = new Manager;
            $capsule->addConnection($settings['db']);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },

        // Register provider
        Messages::class => function () {
            return new Messages();
        },


    ]);
};
