<?php
declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class TrailingSlashMiddleware
{


    /**
     * Example middleware invokable class
     *
     * @param ServerRequest $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $response = $handler->handle($request);

        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path != '/' && substr($path, -1) !== '/') {
            // permanently redirect paths with a trailing slash
            // to their non-trailing counterpart
            //вырезаем или добавляем нужное раскомментировать
            // $uri = $uri->withPath(substr($path, 0, -1));
            $uri = $uri->withPath($path . '/');


            if ($request->getMethod() == 'GET') {
                return $response->withHeader('Location', (string)$uri)->withStatus(301);
            }
        }
        return $response;


    }


}