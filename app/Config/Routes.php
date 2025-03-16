<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//  $routes->get('/', function() {
//     return 'hello';
// });
$routes->get('helper', 'Home::practice_helper');

$routes->group('api', function ($routes) {
    $routes->group('user', function ($routes) {
        $routes->get('', 'Apis\UsersController::index');
        $routes->post('create', 'Apis\UsersController::create');
        $routes->get('(:any)', 'Apis\UsersController::show/$1');
        $routes->put('(:any)', 'Apis\UsersController::update/$1');
        $routes->patch('(:num)', 'Apis\UsersController::changeStatus/$1');
        $routes->delete('(:num)', 'Apis\UsersController::delete/$1');
        $routes->post('(:num)/delete', 'Apis\UsersController::StatusDelete/$1');
        $routes->post('login','Apis\UsersController::login');
    });
});

