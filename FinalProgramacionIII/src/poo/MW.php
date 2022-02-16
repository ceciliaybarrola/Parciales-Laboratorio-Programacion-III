<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once 'Perfil.php';
require_once 'Usuario.php';

class MW
{
    
    public  function VerificarTokenMW(Request $request, RequestHandler $handler)
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = "No se envio token";
        $retorno->status = 403;


        if(isset($request->getHeader('token')[0]) && $request->getHeader('token')[0] !== "null")
        {
            $token = $request->getHeader('token')[0];

            $retorno = AutentificadoraJWT::ValidarJWT($token);
            if($retorno->exito)
            {
                $retorno = json_decode(($handler->handle($request))->getBody());
            }
        }
        
        $newResponse = new ResponseMW($retorno->status);
        $newResponse->getBody()->write(json_encode($retorno));
        return $newResponse->withHeader('Content-type', 'application/json');
    }


}