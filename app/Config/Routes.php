<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Public\Home::index');
$routes->get('admin', 'Admin\Home::index');

// API Routes
$routes->group('api', function ($routes) {
    $routes->get('biddings', 'Api\Bidding::index');
    $routes->get('biddings/(:num)', 'Api\Bidding::show/$1');
    $routes->post('biddings', 'Api\Bidding::create');
    $routes->put('biddings/(:num)', 'Api\Bidding::update/$1');
    $routes->delete('biddings/(:num)', 'Api\Bidding::delete/$1');

    $routes->post('upload', 'Api\Upload::index');

    $routes->get('users', 'Api\User::index');
    $routes->get('users/(:num)', 'Api\User::show/$1');
    $routes->post('users', 'Api\User::create');
    $routes->put('users/(:num)', 'Api\User::update/$1');
    $routes->delete('users/(:num)', 'Api\User::delete/$1');
});
