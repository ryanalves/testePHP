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

$routes->get('/login', 'Home::login');
$routes->get('/logout', 'Home::logout');

$routes->get('/', 'Home::vagas');
$routes->get('/vagas/criar', 'Home::criarVaga', ['filter' => 'adminAuthorizate']);
$routes->get('/vagas/visualizar/(:num)', 'Home::visualizarVaga/$1');
$routes->get('/vagas/editar/(:num)', 'Home::editarVaga/$1', ['filter' => 'adminAuthorizate']);

$routes->get('/usuarios', 'Home::usuarios', ['filter' => 'adminAuthorizate']);
$routes->get('/usuarios/criar', 'Home::criarUsuario', ['filter' => 'adminAuthorizate']);
$routes->get('/usuarios/visualizar/(:num)', 'Home::visualizarUsuario/$1');
$routes->get('/usuarios/editar/(:num)', 'Home::editarUsuario/$1', ['filter' => 'adminAuthorizate']);

$routes->get('/candidaturas', 'Home::candidaturas', ['filter' => 'candidatoAuthorizate']);

$routes->group('api', static function ($routes) {
    $routes->group('auth', static function ($routes) {
        $routes->get('', 'Auth::index', ['filter' => 'authorizate']);
        $routes->post('login', 'Auth::login');
    });

    $routes->group('vaga', static function ($routes) {
        $routes->get('', 'Vaga::listarVagas');
        $routes->get('(:num)', 'Vaga::buscarVaga/$1');
        $routes->get('candidaturas', 'Vaga::listarCandidaturas', ['filter' => 'candidatoAuthorizate']);
        $routes->post('', 'Vaga::criarVaga', ['filter' => 'adminAuthorizate']);
        $routes->post('candidatar/(:num)', 'Vaga::candidatar/$1', ['filter' => 'candidatoAuthorizate']);
        $routes->put('(:num)', 'Vaga::editarVaga/$1', ['filter' => 'adminAuthorizate']);
        $routes->delete('', 'Vaga::deletarVagas', ['filter' => 'adminAuthorizate']);
        $routes->delete('candidatar/(:num)', 'Vaga::cancelarCandidatura/$1', ['filter' => 'candidatoAuthorizate']);
    });

    $routes->group('usuario', static function ($routes) {
        $routes->get('', 'Usuario::listarUsuarios', ['filter' => 'adminAuthorizate']);
        $routes->get('(:num)', 'Usuario::buscarUsuario/$1', ['filter' => 'adminAuthorizate']);
        $routes->post('', 'Usuario::criarUsuario', ['filter' => 'adminAuthorizate']);
        $routes->put('(:num)', 'Usuario::editarUsuario/$1', ['filter' => 'adminAuthorizate']);
        $routes->delete('', 'Usuario::deletarUsuarios', ['filter' => 'adminAuthorizate']);
    });

    $routes->group('candidato', static function ($routes) {
        $routes->get('(:num)', 'Candidato::buscarCandidato/$1', ['filter' => 'adminAuthorizate']);
        $routes->get('', 'Candidato::listarCandidatos', ['filter' => 'adminAuthorizate']);
        $routes->post('', 'Candidato::criarCandidato', ['filter' => 'adminAuthorizate']);
        $routes->put('', 'Candidato::editarCandidato', ['filter' => 'authorizate']);
        $routes->put('(:num)', 'Candidato::editarCandidatoPorId/$1', ['filter' => 'adminAuthorizate']);
        $routes->delete('', 'Candidato::deletarCandidatos', ['filter' => 'adminAuthorizate']);
    });
});
