<?php

declare(strict_types=1);

namespace App\Controllers\Admin;



use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

use App\Controllers\BaseController;

use Slim\Psr7\Response;

//use Slim\Factory\AppFactory;

use Slim\App;

class DashboardController extends BaseController
{


    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $messages = $this->flash->getMessages();
        $response = new Response();
        return $this->view->render($response, 'admin/home.twig', [ 'flash' => $messages]);
    }

}


