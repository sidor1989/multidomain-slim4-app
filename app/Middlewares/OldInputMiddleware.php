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
final class OldInputMiddleware implements MiddlewareInterface
{

    public $view;

    public function __construct(\Slim\Views\Twig $view)
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute('session');
        $session['old'] = $request->getParsedBody();
        $this->view->getEnvironment()->addGlobal('old', $session['old']);

        return $handler->handle($request);
    }
}