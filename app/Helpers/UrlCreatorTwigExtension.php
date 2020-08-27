<?php

namespace App\Helpers;



class UrlCreatorTwigExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var \Slim\Interfaces\RouterInterface
     */
    public $router;


    public function __construct($router)
    {

        $this->router = $router;

    }


    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('url_for', array($this, 'urlFor')),
        ];
    }

    public function urlFor($name, $data = [], $queryParams = [], $appName = 'default')
    {
        return $this->router->urlFor($name, $data, $queryParams);
    }

}
