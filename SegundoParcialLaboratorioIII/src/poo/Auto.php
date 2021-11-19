<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesodatos.php';

class Auto{
    public $id;//int
    public $color;
    public $marca;
    public $precio;//double
    public $modelo;

    /*A nivel de aplicación:
    (POST) Alta de autos. Se agregará un nuevo registro en la tabla autos *.
    Se envía un JSON → auto (color, marca, precio y modelo).
    * ID auto-incremental.
    Retorna un JSON (éxito: true/false; mensaje: string; status: 200/418)
    */
    public function altaAuto(Request $request, Response $response, array $args): Response{
        $objRetorno = new stdClass();
        $objRetorno->Exito = false;
        $objRetorno->Mensaje = "Fallo al agregar";
        $objRetorno->status = 418;

        $arrayDeParametros = $request->getParsedBody();
        $datosJSON = json_decode($arrayDeParametros['auto']);
        $auto = new Auto();
        $auto->color = $datosJSON->color;
        $auto->marca = $datosJSON->marca;
        $auto->precio = $datosJSON->precio;
        $auto->modelo = $datosJSON->modelo;
        
        if($auto->insertarAuto() !== false){
            $objRetorno->Exito = true;
            $objRetorno->Mensaje = "Auto agregado";
            $objRetorno->status = 200;
        }

        $response->withStatus($objRetorno->status);
        $response->getBody()->write(json_encode($objRetorno));
        return $response;
    }


    /*A nivel de ruta (/autos):
    (GET) Listado de autos. Mostrará el listado completo de los autos (array JSON).
    Retorna un JSON (éxito: true/false; mensaje: string; dato: arrayJSON; status: 200/424) */

    public function listadoAutos(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha mostrado con exito el listado";
        $std->dato = '';
        $std->status = 424;
  
        if(($listaUsuarios = Auto::traerListadoAutos()) !== false){
            $std->exito = true;
            $std->mensaje = "se ha mostrado con exito el listado";
            $std->dato = $listaUsuarios;
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');

    }


    /*(DELETE) Borrado de autos por ID.
    Recibe el ID del auto a ser borrado (id_auto, cómo parámetro de ruta).
    Retorna un JSON (éxito: true/false; mensaje: string; status: 200/418) */
    public static function Eliminar(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha eliminado el auto";
        $std->status = 418;
  
        $id = json_decode($args['id_auto']);
        $auto = new Auto();

        if($auto->EliminarAutoBD($id)){
            $std->exito = true;
            $std->mensaje = "se ha eliminado el auto con exito";
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public static function Modificar(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha modificar el auto";
        $std->status = 418;
        $parametro = json_decode($args['auto']);

        $auto = new Auto();
        $auto->id = $parametro->id_auto;
        $auto->color = $parametro->color;
        $auto->marca = $parametro->marca;
        $auto->precio = $parametro->precio;
        $auto->modelo = $parametro->modelo;

        if($auto->ModificarAutoBD($parametro->id_auto, $auto)){
            $std->exito = true;
            $std->mensaje = "Auto modificado";
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    //POO

    public function insertarAuto(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into autos (color,marca,precio,modelo)
                                                        values(:color,:marca,:precio,:modelo)");
		$consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
		$consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':precio',$this->precio, PDO::PARAM_INT);
		$consulta->bindValue(':modelo', $this->modelo, PDO::PARAM_STR);
		$consulta->execute();		

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function traerListadoAutos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from autos");
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "Auto");	
    }

    // ELIMINAR Y MODIFICAR
    public static function EliminarAutoBD($id)
    {
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM autos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            $retorno = true;
        }
        return $retorno;
    }

    public function ModificarAutoBD($id, $auto)
    {
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE autos SET color=:color, 
        marca=:marca, precio=:precio, modelo=:modelo WHERE id=:id");
                                                    
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
        $consulta->bindValue(':color', $auto->color, PDO::PARAM_STR);
        $consulta->bindValue(':marca', $auto->marca, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $auto->precio, PDO::PARAM_STR);
        $consulta->bindValue(':modelo', $auto->modelo, PDO::PARAM_STR);

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $retorno = true;
        }
        return $retorno;
    }


}