<?php

declare(strict_types=1);

namespace App\Controllers\Admin;


use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

use App\Controllers\BaseController;

use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

use Slim\App;
use App\Models\Album;
use App\Models\AlbumsItems;



class AlbumsController extends BaseController
{

    const RULES_VALIDATION = [
        'required' => [
            ['name'],
        ],
    ];

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $albums = Album::all();
        $flash = $this->flash->getMessages();
        $response = new Response();
        return $this->view->render($response, 'admin/albums/index.twig', compact('albums', 'flash'));

    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $flash = $this->flash->getMessages();
        $response = new Response();
        return $this->view->render($response, 'admin/albums/create.twig', compact( 'flash'));
    }

    public function edit(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $album = Album::find($id);
        $albumsItems = $album->album_items;

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/albums/edit.twig', compact('flash', 'album', 'albumsItems'));

    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $album = Album::find($id);
        $data = $request->getParsedBody();
        $album->fill($data);
        $album->save();
        $this->flash->addMessageNow('status', 'Album updated success - ' . $id);
        $flash = $this->flash->getMessages();
        $response = new Response();
        return $this->view->render($response, 'admin/albums/edit.twig', compact('flash', 'album'));
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        if ($this->validate($data, self::RULES_VALIDATION)) {
            Album::create($data);
            $this->flash->addMessage('status', 'Album added success');
        }else {
            $flash = $this->flash->getMessages();
            $response = new Response();
            return $this->view->render($response, 'admin/albums/create.twig', compact('flash'));
        }
        $response = new Response();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('albumsIndex');

        return $response->withHeader('Location', $url);
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {


        $id = $request->getAttribute('id');


        Album::find($id)->delete();
        $this->flash->addMessage('status', 'Album deleted success - ' . $id);

        $response = new Response();

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('albumsIndex');

        return $response->withHeader('Location', $url);

    }



}


