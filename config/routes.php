<?php

declare(strict_types=1);

use App\Controllers\HomePageController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\UsersController;
use App\Controllers\Admin\DomainsController;
use App\Controllers\Admin\ClientsController;
use App\Controllers\Admin\CourseCreatorController;
use App\Controllers\Admin\AlbumsController;
use App\Controllers\Admin\AlbumsItemsController;
use App\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $app) {
    $app->get('/', HomePageController::class . ':home')->setName('home');
    $app->post('/', HomePageController::class . ':sendEmail')->setName('sendEmail');
    $app->get('/auth/', AuthController::class . ':index')->setName('indexAuthAction');
    $app->post('/auth/', AuthController::class . ':auth')->setName('authAction');
    $app->get('/logout/', AuthController::class . ':logout')->setName('logoutAction');

    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('/', DashboardController::class . ':index')->setName('indexAdminAction');
        $group->get('/users/', UsersController::class . ':index')->setName('usersIndex');
        $group->get('/domains/', DomainsController::class . ':index')->setName('domainsIndex');
        $group->get('/domains/create/', DomainsController::class . ':create')->setName('domainsCreate');
        $group->get('/domains/edit/{id}/', DomainsController::class . ':edit')->setName('domainsEdit');
        $group->post('/domains/edit/{id}/', DomainsController::class . ':update')->setName('domainsEditStore');
        $group->post('/domains/delete/{id}/', DomainsController::class . ':delete')->setName('domainsDelete');
        $group->post('/domains/create/', DomainsController::class . ':store')->setName('domainsStore');

        $group->get('/clients/', ClientsController::class . ':index')->setName('clientsIndex');
        $group->get('/clients/create/', ClientsController::class . ':create')->setName('clientsCreate');
        $group->get('/clients/edit/{id}/', ClientsController::class . ':edit')->setName('clientsEdit');
        $group->post('/clients/edit/{id}/', ClientsController::class . ':update')->setName('clientsEditStore');
        $group->post('/clients/delete/{id}/', ClientsController::class . ':delete')->setName('clientsDelete');
        $group->post('/clients/create/', ClientsController::class . ':store')->setName('clientsStore');

        $group->get('/courses/', CourseCreatorController::class . ':index')->setName('coursesIndex');
        $group->get('/courses/create/', CourseCreatorController::class . ':create')->setName('coursesCreate');
        $group->get('/courses/edit/{id}/', CourseCreatorController::class . ':edit')->setName('coursesEdit');
        $group->post('/courses/edit/{id}/', CourseCreatorController::class . ':update')->setName('coursesEditStore');
        $group->post('/courses/delete/{id}/', CourseCreatorController::class . ':delete')->setName('coursesDelete');
        $group->post('/courses/create/', CourseCreatorController::class . ':store')->setName('coursesStore');

        $group->get('/albums/', AlbumsController::class . ':index')->setName('albumsIndex');
        $group->get('/albums/create/', AlbumsController::class . ':create')->setName('albumsCreate');
        $group->get('/albums/edit/{id}/', AlbumsController::class . ':edit')->setName('albumsEdit');
        $group->post('/albums/edit/{id}/', AlbumsController::class . ':update')->setName('albumsEditStore');
        $group->post('/albums/delete/{id}/', AlbumsController::class . ':delete')->setName('albumsDelete');
        $group->post('/albums/create/', AlbumsController::class . ':store')->setName('albumsStore');

        $group->get('/albums-items/{id}/', AlbumsItemsController::class . ':index')->setName('albumsItemsIndex');
        $group->post('/albums-items/delete/{id}/', AlbumsItemsController::class . ':delete')->setName('albumsItemsDelete');
        $group->post('/albums-items/create/{id}/', AlbumsItemsController::class . ':store')->setName('albumsItemsStore');


    })->add(new AuthMiddleware());


};



