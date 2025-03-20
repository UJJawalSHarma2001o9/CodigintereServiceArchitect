<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('api', function ($routes) {
    $routes->group('user', function ($routes) {
        $routes->get('', 'Apis\UsersController::index');
        $routes->post('create', 'Apis\UsersController::create');
        $routes->get('(:any)', 'Apis\UsersController::show/$1');
        $routes->put('(:any)', 'Apis\UsersController::update/$1');
        $routes->patch('(:num)', 'Apis\UsersController::changeStatus/$1');
        $routes->delete('(:num)', 'Apis\UsersController::delete/$1');
        $routes->post('(:num)/delete', 'Apis\UsersController::StatusDelete/$1');
        $routes->post('login', 'Apis\UsersController::login');
        $routes->post('file-upload', 'Apis\UsersController::userFileupload');
    });
});

$routes->post('jwt-test-login', 'Apis\JwtAuthController::login');
$routes->group('jwt-auth-test', ['filter' => 'jwtAuth'], function ($routes) {
    $routes->get('get-users', 'Apis\ClinicController::get_users');
});

$routes->post('auth-login', 'Apis\ApiKeyController::login');

$routes->group('apiKeyAuth', ['filter' => 'apiKeyAuth', 'namespace' => 'App\Controller\Apis'], function ($routes) {
    $routes->get('users', 'UsersController::index');
});