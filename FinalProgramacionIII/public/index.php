<?php
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../src/poo/Usuario.php";
require_once __DIR__ . "/../src/poo/Perfil.php";
require_once __DIR__ . "/../src/poo/MW.php";


$app = AppFactory::create();

$app->get('/', Usuario::class . ':mostrarListadoUsuarios');

$app->post('/usuario', Usuario::class . ':altaUsuario')->add(MW::class.':VerificarTokenMW');
$app->post('/', Perfil::class . ':altaPerfil')->add(MW::class.':VerificarTokenMW');
$app->get('/perfil', Perfil::class . ':listadoPerfiles');


$app->post('/login[/]', Usuario::class . ':loginUsuario');
$app->get('/login[/]', \Usuario::class . '::VerificarJWT');


$app->group('/perfiles', function (RouteCollectorProxy $grupo) {   
    $grupo->delete('[/]', \Perfil::class . '::EliminarPerfil');
    $grupo->put('[/]',\Perfil::class . '::ModificarPerfil');
})->add(MW::class.':VerificarTokenMW');

$app->group('/usuarios', function (RouteCollectorProxy $grupo) {   
    $grupo->delete('[/]', \Usuario::class . '::EliminarUsuario');
    $grupo->post('[/]',\Usuario::class . ':ModificarUsuario');
})->add(MW::class.':VerificarTokenMW');

$app->get('/pdf[/]', \Usuario::class . '::MostrarTodosPdf')->add(MW::class.':VerificarTokenMW');

$app->run();