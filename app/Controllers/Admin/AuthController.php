<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use App\Controllers\BaseController;
use Slim\Psr7\Response;

use App\Models\User;


class AuthController extends BaseController
{


    public function index(ServerRequestInterface $request): ResponseInterface
    {



        $this->flash->addMessageNow('status', 'Sign in please!');

        $messages =  $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'auth.twig', [ 'flash' => $messages]);



    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
        session_destroy();
        $response = new Response();
        return $response->withHeader('Location', '/');
    }

    public function auth(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();


        $response = new Response();

        $user = User::where('email', $data['email'])->first();


        if ($user) {

            if (password_verify($data['password'], $user->password)) {
                $_SESSION['user'] = $user->email;
                $this->flash->addMessage('status', 'Welcome '.$user->login);
                $messages =  $this->flash->getMessages();

                // Redirect to login route
                $url = parent::getRouteParser($request,'indexAdminAction');
                return $this->view->render($response, 'admin/home.twig', [ 'flash' => $messages])->withHeader('Location', $url);
               

            }

        }



        $this->flash->addMessage('status', 'Erorr password or login');
        $messages = $this->flash->getMessages();
        return $this->view->render($response, 'auth.twig', ['flash' => $messages]);


    }

}


