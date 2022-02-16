<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesodatos.php';
require_once 'autentificadoraJWT.php';

class Perfil{
    public $id;
    public $descripcion;
    public $estado;

    public function altaperfil(Request $request, Response $response, array $args): Response{
        $objRetorno = new stdClass();
        $objRetorno->Exito = false;
        $objRetorno->Mensaje = "Fallo al agregar";
        $objRetorno->status = 418;

        $arrayDeParametros = $request->getParsedBody();
        $datosJSON = json_decode($arrayDeParametros['perfil']);
        $perfil = new perfil();
        $perfil->descripcion = $datosJSON->descripcion;
        $perfil->estado = $datosJSON->estado;
        
        if($perfil->insertarperfil() !== false)
        {
            $objRetorno->Exito = true;
            $objRetorno->Mensaje = "perfil agregado";
            $objRetorno->status = 200;
        }

        $response->withStatus($objRetorno->status);
        $response->getBody()->write(json_encode($objRetorno));
        return $response;
    }
    public function listadoPerfiles(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha mostrado con exito el listado";
        $std->dato = '';
        $std->status = 424;
  
        if(($listaUsuarios = perfil::traerListadoperfiles()) !== false)
        {
            $std->exito = true;
            $std->mensaje = "se ha mostrado con exito el listado";
            $std->dato = $listaUsuarios;
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');

    }
    public static function EliminarPerfil(Request $request, Response $response, array $args): Response
    {
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha eliminado el perfil";
        $std->status = 418;

        $id = json_decode($request->getBody())->id_perfil;
        $token = $request->getHeader('token')[0];
        $verificarToken = autentificadoraJWT::ObtenerPayLoad($token);

        $perfil = new perfil();
        if($verificarToken->exito)
        {
            if($perfil->EliminarperfilBD($id))
            {
                $std->exito = true;
                $std->mensaje = "se ha eliminado el perfil con exito";
                $std->status = 200;
            }
        }
		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarPerfil(Request $request, Response $response, array $args): Response
    {
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "No se ha modificado el perfil";
        $std->status = 418;

        $parametro = json_decode($request->getBody())->perfil;
        $id = json_decode($request->getBody())->id_perfil;

        $token = $request->getHeader('token')[0]; 
        $verificarToken = autentificadoraJWT::ObtenerPayLoad($token);

        $perfil = new perfil();
        $perfil->descripcion = $parametro->descripcion;
        $perfil->estado = $parametro->estado;

        if($verificarToken->exito)
        {
            if($perfil->ModificarperfilBD($id, $perfil))
            {
                $std->exito = true;
                $std->mensaje = "perfil modificado";
                $std->status = 200;
            }
        }
		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function insertarperfil()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into perfiles (descripcion,estado)
                                                        values(:descripcion,:estado)");
		$consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);

		$consulta->execute();		

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function traerListadoperfiles()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from perfiles");
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "perfil");	
    }

    public static function EliminarperfilBD($id)
    {
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM perfiles WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        if ($consulta->rowCount() > 0) 
        {
            $retorno = true;
        }
        return $retorno;
    }

    public function ModificarperfilBD($id, $perfil)
    {
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE perfiles SET descripcion=:descripcion, 
        estado=:estado WHERE id=:id");
                                                    
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
        $consulta->bindValue(':descripcion', $perfil->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $perfil->estado, PDO::PARAM_INT);

        $consulta->execute();

        if ($consulta->rowCount() > 0) 
        {
            $retorno = true;
        }
        return $retorno;
    }


}