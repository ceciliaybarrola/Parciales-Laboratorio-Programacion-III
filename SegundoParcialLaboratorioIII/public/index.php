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

//para el lado del front
$twig = Twig::create('../src/views', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/front-end-login', function (Request $request, Response $response, array $args) : Response {  

    $view = Twig::fromRequest($request);
  
    return $view->render($response, 'login.html');
    
  });

$app->get('/front-end-registro', function (Request $request, Response $response, array $args) : Response {  

  $view = Twig::fromRequest($request);
  
  return $view->render($response, 'registro.html');
    
});

$app->get('/front-end-principal', function (Request $request, Response $response, array $args) : Response {  

  $view = Twig::fromRequest($request);
  
  return $view->render($response, 'principal.php');
    
});
//termina front



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
Crear los siguientes Middlewares para que a partir del m??todo que retorna el listado de
usuarios (clase Usuario ??NO hacer nuevos m??todos!):

1.- (GET) Retorna una tabla html con el contenido completo de los usuarios (excepto la clave).
(clase MW - m??todo de clase).
2.- (POST) Solo si es un ???propietario???, se retornar?? el listado del punto anterior, pero en
formato .pdf. (clase MW - m??todo de instancia). Se env??a c??mo par??metro de petici??n un JSON
??? usuario (con todos los datos del usuario, a excepci??n de la clave)

A nivel de ruta (/autos):
Crear los siguientes Middlewares para que a partir del m??todo que retorna el listado de autos
(clase Auto ??NO hacer nuevos m??todos!):
1.- (GET) Retorna una tabla html con el contenido completo de los autos. (clase MW - m??todo de
instancia). */
$app->group('/tablas', function (RouteCollectorProxy $grupo) {   
    $grupo->map(['GET', 'POST'],'/usuarios', Usuario::class . ':mostrarListadoUsuarios')->add(MW::class.':listadoUsuarios');
    $grupo->get('/autos', Auto::class . ':listadoAutos')->add(MW::class.':listadoAutos');
});

$app->run();