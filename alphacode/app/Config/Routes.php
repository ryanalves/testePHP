<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/auth', 'Auth::index', ['filter' => 'authFilter']);

$routes->post('/auth/signin', 'Auth::login');
