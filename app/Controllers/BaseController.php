<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

use \Illuminate\Database\Capsule\Manager;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;
use Slim\Flash\Messages;

use Slim\Routing\RouteContext;


abstract class BaseController implements RequestHandlerInterface
{
    protected $container;

    protected $logger;
    protected $flash;
    protected $view;
    protected $db;


    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;

        $this->logger = $c->get(LoggerInterface::class);
        $this->flash = $c->get(Messages::class);
        $this->view = $c->get(Twig::class);
        $this->db = $c->get(Manager::class);


    }

    public function validate($data, $rules)
    {
        $v = new \Valitron\Validator($data);
        $v->rules($rules);

        if (!$v->validate()) {
            $arrErrors = $v->errors();
            foreach ($arrErrors as $error => $v) {
                $strValue = implode('|', $v);
                $this->flash->addMessageNow($error, $strValue);
            }
            return false;
        }
        return true;

    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Home page handler dispatched');
        $name = $request->getAttribute('name', 'world');
        $response = new Response();
        $response->getBody()->write("Hello $name");
        return $response;
    }

    public function getRouteParser(ServerRequestInterface $request, $urlFor)
    {

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $url = $routeParser->urlFor($urlFor);

        return $url;
    }


}
