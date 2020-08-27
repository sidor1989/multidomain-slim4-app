<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use App\Controllers\BaseController;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

use App\Models\Client;
use App\Models\Domain;


class ClientsController extends BaseController
{


    const RULES_VALIDATION = [
        'required' => [
            ['name'],
            ['sub_name'],

        ],
        'max' => [
            ['imgSize', 50000]
        ],
    ];

    public function index(ServerRequestInterface $request): ResponseInterface
    {

        $clients = Client::all();

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/clients/index.twig', compact('clients', 'flash'));

    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $domains = Domain::all();
        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/clients/create.twig', compact('clients', 'flash', 'domains'));
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {

        $id = $request->getAttribute('id');

        $directory = $this->container->get('settings')['upload_directory'];

        Client::find($id)->remove($directory);
        $this->flash->addMessageNow('status', 'Client deleted success - ' . $id);

        $response = new Response();

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('clientsIndex');

        return $response->withHeader('Location', $url);

    }


    public function edit(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $client = Client::find($id);
        $domains = Domain::all();

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/clients/edit.twig', compact('flash', 'client', 'domains'));

    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {

        $id = $request->getAttribute('id');

        $client = Client::find($id);
        $domains = Domain::all();

        $data = $request->getParsedBody();


        $directory = $this->container->get('settings')['upload_directory'];

        //получаем размер загруженного изображени для валидации
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['img'];
        $data['imgSize'] = $uploadedFile->getSize();

        if ($this->validate($data, self::RULES_VALIDATION)) {

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $client->removeImage($directory);
                $client->img = $this->moveUploadedFile($directory, $uploadedFile);
                $client->save();
                $this->flash->addMessageNow('status', 'Iamage added success ' . $client->img);

            }

            $client->fill($data);
            $client->save();


            $this->flash->addMessageNow('status', 'client udated success - ' . $id);
        }


        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/clients/edit.twig', compact('flash', 'client', 'domains'));

    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        if ($this->validate($data, self::RULES_VALIDATION)) {


            $directory = $this->container->get('settings')['upload_directory'];

            //получаем размер загруженного изображени для валидации
            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['img'];
            $data['imgSize'] = $uploadedFile->getSize();

            $client = Client::create($data);

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $client->img = $this->moveUploadedFile($directory, $uploadedFile);
                $client->save();
                $this->flash->addMessageNow('status', 'Iamage added success ' . $client->img);

            }


            $this->flash->addMessageNow('status', 'client added success');
        }

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/clients/create.twig', compact('flash'));
    }


    public function moveUploadedFile($directory, $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(10)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

}


