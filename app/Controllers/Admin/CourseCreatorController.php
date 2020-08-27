<?php

declare(strict_types=1);

namespace App\Controllers\Admin;


use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

use App\Controllers\BaseController;

use Slim\Psr7\Response;

use Slim\App;
use App\Models\Domain;
use App\Models\Course;


class CourseCreatorController extends BaseController
{

    public function index(ServerRequestInterface $request): ResponseInterface
    {

        $courses = Course::all();

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/courses/index.twig', compact('courses', 'flash'));

    }

    public function edit(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $course = Course::find($id);
        $domains = Domain::all();

        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/courses/edit.twig', compact('flash', 'course', 'domains'));

    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {

        $id = $request->getAttribute('id');

        $course = Course::find($id);
        $domains = Domain::all();

        $data = $request->getParsedBody();


        $course->fill($data);
        $course->save();

        $this->flash->addMessageNow('status', 'course udated success - ' . $id);


        $flash = $this->flash->getMessages();

        $response = new Response();
        return $this->view->render($response, 'admin/courses/edit.twig', compact('flash', 'course', 'domains'));

    }


    public function generateList(ServerRequestInterface $request)
    {


        $results = $this->getResultFromApi();

        foreach ($results as $result) {
            //ищем курс по айдишнику , если не находим создаем новый
            $сourse = Course::firstOrCreate(['api_foriegn_key' => $result['id']]);


            //заполняем свойства объекта
            $сourse->title = $result['pagetitle'];
            $сourse->lectors = $result['value']['lectors'][0];
            $сourse->city = $result['value']['city'][0];
            $сourse->img = $result['thumb'];
            $сourse->date1 = $result['date1'];
            $сourse->date2 = $result['date2'];
            $сourse->tags = $result['tags'];
            $сourse->size = $result['size'];
            $сourse->alias = $result['alias'];
            $сourse->active = 1;
            $сourse->api_foriegn_key = $result['id'];
            //Если имееться домен с таким городом то заполним foreign_key
            $domain = Domain::where('name_town_cyrilic', $сourse->city)->first();
            if ($domain) {
                $сourse->domain_id = $domain->id;
            }
            //сохраняем в базу
            $сourse->save();

        }
        $response = new Response;
        return $response;
    }

    public function getResultFromApi()
    {


        $apiKey = 'b84d0d84342201f35c20fghfgh';
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'x-auth-token: ' . $apiKey,
        ];

        $ch = curl_init();
// GET запрос указывается в строке URL
        curl_setopt($ch, CURLOPT_URL, "https://site.ru/api/courses");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:56.0) Gecko/20100101 Firefox/56.0');


        $cnt = curl_exec($ch);
        curl_close($ch);
//декодируем из джсона в масссив
        $result = json_decode($cnt, true);
        if ($result) {
            $this->logger->debug('Curl executed');
        }

        return $result;
    }

}


