<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use App\Controllers\BaseController;
use Slim\Psr7\Response;

use App\Models\User;


class UsersController extends BaseController
{


    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $users = User::all();

        $messages =  $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/users.twig', [ 'flash' => $messages, 'users' => $users]);

    }

}


