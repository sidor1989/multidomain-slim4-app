<?php

declare(strict_types=1);

namespace App\Controllers;


use mysql_xdevapi\Exception;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

use App\Models\Domain;
use App\Models\Album;

use Slim\Flash\Messages;
use Slim\Psr7\Response;

class HomePageController extends BaseController
{


    public function home(ServerRequestInterface $request): ResponseInterface
    {

        $domainsList = Domain::where('active', 1)->get();

        $response = new Response();


        $subDomainArr = explode(".", $request->getUri()->getHost());


        if ((count($subDomainArr) > 2)) {
            //извлекаем поддомен строкой на крилице
            $subDomain = idn_to_utf8(array_shift($subDomainArr), IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

            $domain = Domain::where('name_domain', $subDomain)->firstOrFail();

        } else {
            $domain = Domain::where('name_domain', 'москва')->firstOrFail();
        }
        //получаем данные клиентов
        $clients = $domain->clients->where('active', 1);
        $courses = $domain->courses->where('active', 1);
        if ($domain->album()->where('active', 1)->get()->count() > 0 && $domain->album_id) {
            $albumItems = $domain->album->find($domain->album_id)->albumItems()->get();
        }

        if ($domain->content_2) {
            $domain->content_2 = $pieces = explode(" ", $domain->content_2);
        } else {
            $domain->content_2 = [5000, 150, 4500];
        }


        $this->logger->info('Home page handler dispatched');

        $flash = $this->container->get(Messages::class);

        $flash = $flash->getMessages();

        return $this->view->render($response, 'hello.twig', compact('flash', 'domainsList', 'domain', 'clients', 'courses', 'albumItems'));


    }

    public function sendEmail(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        $to = $data['emailtarget'];
        if (isset($data['surname'])) { // проверяем наше поле на пустоту
            return die();
        }
        if ($data['check'] != '662233') exit('Spam decected');
        if ($data && !empty($data)) {
            $name = isset($data['name']) ? $data['name'] : '';
            $email = isset($data['email']) ? $data['email'] : '';
            $comments = isset($data['comments']) ? $data['comments'] : '';
            $content = 'Имя: ' . $name . "\n";
            $content .= 'Email: ' . $email . "\n";
            $content .= 'Сообщение: ' . $comments . "\n";
            $subject = 'Вопрос с сайта';
            $headers = "From: Сайт <info@site.ru>";
            mail($to, $subject, $content, $headers);

        }
        $response = new Response();
        $response->getBody()->write("Спасибо, Мы свяжемся с Вами в ближайшее время!");
        return $response;
    }


}


