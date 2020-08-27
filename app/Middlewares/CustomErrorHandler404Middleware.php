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
final class CustomErrorHandler404Middleware
{
    protected $view;

    public function __construct(\Slim\Views\Twig  $view)
    {
        $this->view = $view;
    }
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
       // var_dump($app);
        $response = new \Slim\Psr7\Response();
        return $this->view->render($response, 'err404.twig')->withStatus(404);


    }
}