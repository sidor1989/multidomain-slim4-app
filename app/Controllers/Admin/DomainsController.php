<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use App\Controllers\BaseController;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

use App\Models\Domain;


class DomainsController extends BaseController
{

    const RULES_VALIDATION = [
        'required' => [
            ['name_domain'],
            ['name_town_cyrilic'],
            ['name_diler'],
            ['towns'],
            ['map_coordinats'],
            ['active'],
        ],

        'regex' => [
            ['name_town_cyrilic', '/[А-ЯЁ-][а-яё-]*/']
        ],
    ];

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $domains = Domain::all();

        $messages = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/domains/index.twig', ['flash' => $messages, 'domains' => $domains]);

    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {


        $messages = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/domains/create.twig', ['flash' => $messages]);

    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {


        $id = $request->getAttribute('id');


        Domain::find($id)->delete();
        $this->flash->addMessageNow('status', 'Domain deleted success - ' . $id);
        //  $flash =  $this->flash->getMessages();

        $response = new Response();

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('domainsIndex');

        return $response->withHeader('Location', $url);

    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {

        $id = $request->getAttribute('id');

        $domain = Domain::find($id);


        $data = $request->getParsedBody();


        if ($this->validate($data, self::RULES_VALIDATION)) {
            $domain->fill($data);
            $domain->save();
            $this->flash->addMessageNow('status', 'Domain updated success - ' . $id);
        }


        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/domains/edit.twig', compact('flash', 'domain'));

    }


    public function edit(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $domain = Domain::find($id);


        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/domains/edit.twig', compact('flash', 'domain'));

    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        if ($this->validate($data, self::RULES_VALIDATION)) {
            Domain::create($data);
            $this->flash->addMessageNow('status', 'Domain added success');
        }

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/domains/create.twig', compact('flash'));
    }

}


