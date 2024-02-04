<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
** Filtros de autenticação **
* authenticate - identifica o usuário
* authorizate - autoriazação de usuário comum
* adminAuthorizate - autorização de usuário administrador
*/
$routes->group('api', static function ($routes) {
    $routes->group('auth', static function ($routes) {
        $routes->get('', 'Auth::index', ['filter' => 'authorizate']);
        $routes->post('signin', 'Auth::login');
    });

    $routes->group('vaga', static function ($routes) {
        $routes->get('(:num)', 'Vaga::buscarVaga/$1', ['filter' => 'authenticate']);
        $routes->get('', 'Vaga::listarVagas', ['filter' => 'authenticate']);
        $routes->post('', 'Vaga::criarVaga', ['filter' => 'authorizate']);
        $routes->put('(:num)', 'Vaga::editarVaga/$1', ['filter' => 'authorizate']);
        $routes->delete('', 'Vaga::deletarVagas', ['filter' => 'authorizate']);
    });

    $routes->group('usuario', static function ($routes) {
        $routes->get('(:num)', 'Usuario::buscarUsuario/$1', ['filter' => 'adminAuthorizate']);
        $routes->get('', 'Usuario::listarUsuarios', ['filter' => 'adminAuthorizate']);
        $routes->post('', 'Usuario::criarUsuario', ['filter' => 'adminAuthorizate']);
        $routes->put('(:num)', 'Usuario::editarUsuario/$1', ['filter' => 'adminAuthorizate']);
        $routes->delete('', 'Usuario::deletarUsuarios', ['filter' => 'adminAuthorizate']);
    });
});
