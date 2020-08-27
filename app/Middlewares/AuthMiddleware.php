<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use \Slim\Flash\Messages;

class AuthMiddleware
{


    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $session = $request->getAttribute('session'); // get the session from the request
   
        if (!isset($session[ 'user' ])) {

            $response = $handler->handle($request);

            // Redirect to login route
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('indexAuthAction');

            return $response->withHeader('Location', $url);
        } else {
            return  $handler->handle($request);
        }
    }
}