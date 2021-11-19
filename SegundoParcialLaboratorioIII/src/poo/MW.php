<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'auto.php';
require_once 'usuario.php';

class MW
{
    
    
    public static function verificarCamposVacios(Request $request, RequestHandler $handler)
    {
        $objDelaRespuesta = new stdClass();
        $objDelaRespuesta->exito = false;
        $objDelaRespuesta->mensaje = "Los campos estan vacios";
        $objDelaRespuesta->status = 409;

        $arrayDeParametros = $request->getParsedBody();
        $datos = null;

        if(isset($arrayDeParametros['usuario'])){
            $datos = json_decode($arrayDeParametros['usuario']);
        }else if(isset($arrayDeParametros['user'])){
            $datos = json_decode($arrayDeParametros['user']);
        }

        if($datos !== null){

            if(strlen($datos->correo) === 0 && strlen($datos->clave) !== 0)
            {
                $objDelaRespuesta->mensaje = "El campo correo esta vacio";
            }
            else if(strlen($datos->correo) !== 0 && strlen($datos->clave) === 0)
            {
                $objDelaRespuesta->mensaje = "El campo clave esta vacio";
            }
            else if(strlen($datos->correo) === 0 && strlen($datos->clave) === 0)
            {
                $objDelaRespuesta->mensaje = "Tanto la clave como el correo estan vacios";
            }
            else
            {
                $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());
            }
        }

        $newResponse = new ResponseMW($objDelaRespuesta->status);
        $newResponse->getBody()->write(json_encode($objDelaRespuesta));
        return $newResponse->withHeader('Content-type', 'application/json');
    }



    public function VerificarExistenciaCorreoClave(Request $request, RequestHandler $handler)
    {
        $objDelaRespuesta = new stdClass();
        $objDelaRespuesta->exito = false;
        $objDelaRespuesta->mensaje = "El correo no existe en la bd";
        $objDelaRespuesta->status = 403;

        $arrayDeParametros = $request->getParsedBody();
        $datos = json_decode($arrayDeParametros['user']);

        if(Usuario::TraerUnUsuario($datos->correo, $datos->clave) !== false)
        {
            $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());
        }

        $newResponse = new ResponseMW($objDelaRespuesta->status);
        $newResponse->getBody()->write(json_encode($objDelaRespuesta));
        return $newResponse->withHeader('Content-type', 'application/json');
    }


    public static function verificarCorreo(Request $request, RequestHandler $handler)
    {
        $objDelaRespuesta = new stdClass();
        $objDelaRespuesta->exito = false;
        $objDelaRespuesta->mensaje = "El correo ya existe en la base de datos";
        $objDelaRespuesta->status = 403;

        $params = $request->getParsedBody();
        $datos = json_decode($params['usuario']);

        if(Usuario::traerUnUsuarioPorCorreo($datos->correo) != true)
        {
            $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());
        }

        $newResponse = new ResponseMW($objDelaRespuesta->status);
        $newResponse->getBody()->write(json_encode($objDelaRespuesta));
        return $newResponse->withHeader('Content-type', 'application/json');
    }

    public static function listadoUsuarios(Request $request, RequestHandler $handler)
    {
        if($request->getMethod() === "GET") 
        {
            if(($listaUsuarios = Usuario::traerTodos()) !== false){
                $tabla="<table border='5'><tr><td>ID</td><td>CORREO</td><td>NOMBRE</td><td>APELLIDO</td><td>PERFIL</td><td>FOTO</td></tr>";
                    foreach ($listaUsuarios as $usuario) { 
                        $tabla.='<tr><td>'.$usuario["id"].'</td><td>'.$usuario["correo"].'</td><td>'.$usuario["nombre"].'</td><td>'.$usuario["apellido"].'</td><td>'.$usuario["perfil"]."</td><td><img src='./fotos/".$usuario["foto"]."' height='100px' width='100px'></td></tr>";
                    }
                    $tabla.='</table>';
                $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());

            }
        }
        else if($request->getMethod() === "POST")
        {
            $respuesta = 'Entro por POST';
        }


        $params = $request->getParsedBody();
        $datos = json_decode($params['usuario']);

        if(($listaUsuarios = Usuario::traerTodos()) !== false)
        {
            $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());

        }

        $newResponse = new ResponseMW($objDelaRespuesta->status);
        $newResponse->getBody()->write(json_encode($objDelaRespuesta));
        return $newResponse->withHeader('Content-type', 'application/json');
    }
    public static function listadoAutos(Request $request, RequestHandler $handler)
    {
        $objDelaRespuesta = new stdClass();
        $objDelaRespuesta->exito = false;
        $objDelaRespuesta->mensaje = "El correo ya existe en la base de datos";
        $objDelaRespuesta->status = 403;

        $params = $request->getParsedBody();
        $datos = json_decode($params['usuario']);

        if(Usuario::traerUnUsuarioPorCorreo($datos->correo) != true)
        {
            $objDelaRespuesta = json_decode(($handler->handle($request))->getBody());
        }

        $newResponse = new ResponseMW($objDelaRespuesta->status);
        $newResponse->getBody()->write(json_encode($objDelaRespuesta));
        return $newResponse->withHeader('Content-type', 'application/json');
    }



}