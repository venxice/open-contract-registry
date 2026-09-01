<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Public\Home::index');
$routes->get('admin', 'Admin\Home::index');
$routes->get('admin/login', 'Admin\Login::index');

// API Auth Routes
$routes->post('api/auth/login', 'Api\Auth::login');
$routes->post('api/auth/logout', 'Api\Auth::logout');
$routes->get('api/auth/check', 'Api\Auth::check');
$routes->post('api/auth/google', 'Api\Auth::google');
$routes->get('api/auth/google/callback', 'Api\Auth::googleCallback');

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

    $routes->get('audit-logs', 'Api\AuditLog::index');
});
