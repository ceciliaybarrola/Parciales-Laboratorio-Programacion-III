<?php
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../src/poo/Usuario.php";
require_once __DIR__ . "/../src/poo/Auto.php";
require_once __DIR__ . "/../src/poo/MW.php";


$app = AppFactory::create();

$app->get('/', Usuario::class . ':mostrarListadoUsuarios');

//Los Middlewares 1 y 3 
$app->post('/usuarios', Usuario::class . ':altaUsuario')->add(MW::class.'::verificarCorreo')->add(MW::class.'::verificarCamposVacios');
$app->post('/', Auto::class . ':altaAuto');
$app->get('/autos', Auto::class . ':listadoAutos');
//Los Middlewares 1 y 2.
$app->post('/login[/]', Usuario::class . ':loginUsuario')->add(MW::class.':VerificarExistenciaCorreoClave')->add(MW::class.'::verificarCamposVacios');

$app->group('/cars', function (RouteCollectorProxy $grupo) {   
    $grupo->delete('/{id_auto}', \Auto::class . '::Eliminar');
    //$grupo->put('[/]', Auto::class . '::Modificar');
    $grupo->put('/{auto}',\Auto::class . ':Modificar');
});

//Parte 03
$app->group('/users', function (RouteCollectorProxy $grupo) {   
    $grupo->post('/delete', \Auto::class . '::EliminarUsuario');
    $grupo->post('/edit', Auto::class . '::ModificarUsuario');
});



/**Parte 04
Crear, a nivel de grupo (/tablas):
A nivel de ruta (/usuarios):
Crear los siguientes Middlewares para que a partir del método que retorna el listado de
usuarios (clase Usuario ¡NO hacer nuevos métodos!):

1.- (GET) Retorna una tabla html con el contenido completo de los usuarios (excepto la clave).
(clase MW - método de clase).
2.- (POST) Solo si es un ‘propietario’, se retornará el listado del punto anterior, pero en
formato .pdf. (clase MW - método de instancia). Se envía cómo parámetro de petición un JSON
→ usuario (con todos los datos del usuario, a excepción de la clave)

A nivel de ruta (/autos):
Crear los siguientes Middlewares para que a partir del método que retorna el listado de autos
(clase Auto ¡NO hacer nuevos métodos!):
1.- (GET) Retorna una tabla html con el contenido completo de los autos. (clase MW - método de
instancia). */
$app->group('/tablas', function (RouteCollectorProxy $grupo) {   
    $grupo->map(['GET', 'POST'],'/usuarios', Usuario::class . ':mostrarListadoUsuarios')->add(MW::class.':listadoUsuarios');
    $grupo->get('/autos', Auto::class . ':listadoAutos')->add(MW::class.':listadoAutos');
});

$app->run();