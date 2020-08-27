<?php

declare(strict_types=1);

use Slim\App;
use App\Middlewares\AddFlashMessageMiddleware;
use App\Middlewares\AddSessionMiddleware;
use App\Middlewares\TrailingSlashMiddleware;
use App\Middlewares\OldInputMiddleware;
use App\Middlewares\CustomErrorHandler404Middleware;
use App\Middlewares\CustomErrorHandlerEloquentFindOrFallMiddleware;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;


return static function (App $app) {

    $app->addRoutingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
   

    $errorMiddleware->setErrorHandler(Illuminate\Database\Eloquent\ModelNotFoundException::class, CustomErrorHandlerEloquentFindOrFallMiddleware::class);
   // $errorMiddleware->setDefaultErrorHandler(CustomErrorHandler404Middleware::class);


    $app->addBodyParsingMiddleware();

    $app->add(TwigMiddleware::createFromContainer($app,Twig::class));
    //$app->add(new OldInputMiddleware());
    $app->add(OldInputMiddleware::class);
    $app->add(new TrailingSlashMiddleware());
    $app->add(new AddSessionMiddleware);




};
