<?php

declare(strict_types=1);

namespace App\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Middleware.
 */
final class CustomErrorHandlerEloquentFindOrFallMiddleware
{

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function __invoke( ServerRequestInterface $request,
                              \Throwable $exception,
                              bool $displayErrorDetails,
                              bool $logErrors,
                              bool $logErrorDetails) : ResponseInterface
    {
        $response = new \Slim\Psr7\Response();
        $response =  $response->withHeader('Location', 'https://сайт.рф/')->withStatus(301);
        return $response;
    }
}