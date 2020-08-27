<?php

declare(strict_types=1);

namespace App\Controllers\Admin;


use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

use App\Controllers\BaseController;

use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

use Slim\App;
use App\Models\AlbumItem;
use App\Models\Album;



class AlbumsItemsController extends BaseController
{

    const RULES_VALIDATION = [
        'max' => [
            ['imgSize', 150000]
        ],
    ];

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $album_id = $request->getAttribute('id');


        if (AlbumItem::where('album_id', $album_id)->count() > 0) {
            $albumsItems = AlbumItem::where('album_id', $album_id)->get();
        }


        $flash = $this->flash->getMessages();
        $response = new Response();

        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {


            $response->getBody()->write($albumsItems->toJson());
            return $response
                ->withHeader('Content-Type', 'application/json');
        } else {
            return $this->view->render($response, 'admin/albums/index.twig', compact('albumsItems', 'flash'));
        }


    }




    public function store(ServerRequestInterface $request): ResponseInterface
    {

        $data = [];

        $data['album_id'] = $request->getAttribute('id');

        $directory = $this->container->get('settings')['upload_directory'];

        //получаем размер загруженного изображени для валидации
        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['file'];
        $data['imgSize'] = $uploadedFile->getSize();

        if ($this->validate($data, self::RULES_VALIDATION)) {
           $albumItem =  AlbumItem::create($data);
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $albumItem->filename = $this->moveUploadedFile($directory, $uploadedFile);
                $albumItem->save();
                $this->flash->addMessage('status', 'Image added success ' . $albumItem->img);
            }
            $this->flash->addMessage('status', 'Album added success');
        }else {
            $flash = $this->flash->getMessages();
            $response = new Response();
            return $this->view->render($response, 'admin/albums/create.twig', compact('flash'));
        }

        $response = new Response();

        if ($request->getHeader('X-Requested-With') === 'XMLHttpRequest') {
            return $response;
        } else {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('albumsIndex');
            return $response->withHeader('Location', $url);
        }

    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {


        $directory = $this->container->get('settings')['upload_directory'];

        $id = $request->getAttribute('id');


        $albumItem =  AlbumItem::find($id);
        $albumItem->removeImage($directory);
        $albumItem->delete();
        $this->flash->addMessage('status', 'AlbumItem deleted success - ' . $id);

        $response = new Response();


        if ($request->getHeader('X-Requested-With') === 'XMLHttpRequest') {
            return $response;
        } else {
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('albumsIndex');
            return $response->withHeader('Location', $url);
        }



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


