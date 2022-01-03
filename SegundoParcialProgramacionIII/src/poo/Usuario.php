<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesodatos.php';

class Usuario{
    public $id;
    public $correo;
    public $clave;
    public $nombre;
    public $apellido;
    public $perfil;
    public $foto;

    /*(POST) Alta de usuarios. Se agregará un nuevo registro en la tabla usuarios *.
    Se envía un JSON → usuario (correo, clave, nombre, apellido, perfil**) y foto.
    La foto se guardará en ./src/fotos, con el siguiente formato: correo_id.extension.
    Ejemplo: ./src/fotos/juan@perez_152.jpg
    * ID auto-incremental. ** propietario, encargado y empleado.
    Retorna un JSON (éxito: true/false; mensaje: string; status: 200/418) */

    public function altaUsuario(Request $request, Response $response, array $args): Response {
        $arrayDeParametros = $request->getParsedBody();

        $std= new stdclass();
        $std->exito = false;
        $std->mensaje = "no se ha agregado al usuario";
        $std->status = 418;

		$parametro = $arrayDeParametros['usuario'];
        $parametro = json_decode($parametro);
        
        $usuario = new Usuario();
        $usuario->correo = $parametro->correo;
        $usuario->clave = $parametro->clave;
        $usuario->nombre = $parametro->nombre;
        $usuario->apellido = $parametro->apellido;
        $usuario->perfil = $parametro->perfil;

        $destino = __DIR__ . "/../fotos/";
        $archivos = $request->getUploadedFiles();
        if(($id = $usuario->InsertarUsuario()) !== false){

            $nombreAnterior = $archivos['foto']->getClientFilename();
            $extension = explode(".", $nombreAnterior);

            $extension = array_reverse($extension);

            $archivos['foto']->moveTo($destino . $usuario->correo . "_" . $id . "." . $extension[0]);
            $usuario->foto = "/../fotos/" . $usuario->correo . "_" . $id . "." . $extension[0];
		
            Usuario::ActualizarBDFoto($usuario->foto, $id);

            $std->exito = true;
            $std->mensaje = "Agregado el usuario exitosamente";
            $std->status = 200;

        }
        $response->withStatus($std->status);
        $response->getBody()->write(json_encode($std));
        return $response;
    }


    /*nivel de aplicación:
    (GET) Listado de usuarios. Mostrará el listado completo de los usuarios (array JSON).
    Retorna un JSON (éxito: true/false; mensaje: string; dato: arrayJSON; status: 200/424)*/

    public function mostrarListadoUsuarios(Request $request, Response $response, array $args): Response {
        $std = new stdClass();
        
        $std->exito = false;
        $std->mensaje = "no se ha mostrado con exito el listado";
        $std->dato = '';
        $std->status = 424;
  
        if(($listaUsuarios = Usuario::traerTodos()) !== false){
            $std->exito = true;
            $std->mensaje = "se ha mostrado con exito el listado";
            $std->dato = $listaUsuarios;
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');

    }

    /*A nivel de ruta (/login):
    (POST) Se envía un JSON → user (correo y clave) y retorna un JSON (éxito: true/false; usuario:
    JSON (con todos los datos del usuario, a excepción de la clave) / null; status: 200/403)
    */

    public function loginUsuario(Request $request, Response $response, array $args): Response {
        $std = new stdClass();
        $std->exito = false;
        $std->usuario = null;
        $std->status = 403;
        
        $arrayDeParametros = $request->getParsedBody();
        $datos = json_decode($arrayDeParametros['user']);

        if(Usuario::verificarLogin($datos->correo,$datos->clave)){
            $std->exito = true;

            $parametro =  Usuario::TraerUnUsuarioSinClave($datos->correo,$datos->clave);
            $objSinClave = new stdClass();
            $objSinClave->id=$parametro->id;
            $objSinClave->correo=$parametro->correo;
            $objSinClave->nombre=$parametro->nombre;
            $objSinClave->apellido=$parametro->apellido;
            $objSinClave->perfil=$parametro->perfil;
            $objSinClave->foto=$parametro->foto;

            $std->usuario = $objSinClave;
            $std->status = 200;

        }
        $newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    
    }


    /*(POST) Borrado de usuarios por ID.
    Recibe el ID del usuario a ser borrado en un JSON → usuario (id_usuario) pasado cómo
    parámetro de la petición (NO cómo parámetro de ruta)
    Retorna un JSON (éxito: true/false; mensaje: string; status: 200/418) */
    public static function Eliminar(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha eliminado el usuario";
        $std->status = 418;
  
        $id = json_decode($args['usuario']);
        $usuario = new Usuario();

        if($usuario->EliminarUsuarioBD($id->id_usuario)){
            $std->exito = true;
            $std->mensaje = "se ha eliminado el usuario con exito";
            $std->status = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

   /* (POST) Modificación de usuarios.
    Se envía un JSON → usuario (id_usuario, correo, clave, nombre, apellido, perfil) y foto.
    La foto se guardará en ./src/fotos, con el siguiente formato:
    correo_id_modificacion.extension.
    Ejemplo: ./src/fotos/juan@perez_152_modificacion.jpg
    Retorna un JSON (éxito: true/false; mensaje: string; status: 200/418)*/
    public static function Modificar(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha modificado el auto";
        $std->status = 418;

        $arrayDeParametros = $request->getParsedBody();
        $parametro = json_decode($arrayDeParametros['usuario']);

        $usuario = new Usuario();
        $usuario->id = $parametro->id_usuario;
        $usuario->correo = $parametro->correo;
        $usuario->clave = $parametro->clave;
        $usuario->nombre = $parametro->nombre;
        $usuario->apellido = $parametro->apellido;
        $usuario->perfil = $parametro->perfil;

        $archivos = $request->getUploadedFiles();

        if($usuario->ModificarUsuarioBD($parametro->id_usuario, $usuario)){
            $destino = __DIR__ . "/../fotos/";
            $nombreAnterior = $archivos['foto']->getClientFilename();
            $extension = explode(".", $nombreAnterior);

            $extension = array_reverse($extension);

            $archivos['foto']->moveTo($destino . $usuario->correo . "_" . $usuario->id. "_modificacion" . "." . $extension[0]);
            $usuario->foto = "/../fotos/" . $usuario->correo . "_" . $usuario->id . "_modificacion". "." . $extension[0];
            
            Usuario::ActualizarBDFoto($usuario->foto, $usuario->id);

            $std->exito = true;
            $std->mensaje = "Usuario modificado";
            $std = 200;
        }

		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }



    /*******************--- FUNCIONES POO ---*****************/ 

    public static function ActualizarBDFoto($newPath, $id)
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET foto=:foto WHERE id=:id");

		$consulta->bindValue(':id',$id, PDO::PARAM_INT);
        $consulta->bindValue(':foto',$newPath, PDO::PARAM_STR);

		return $consulta->execute();
    }


    public function insertarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (correo,clave,nombre,apellido,perfil,foto)
                                                        values(:correo,:clave,:nombre,:apellido,:perfil,:foto)");
		$consulta->bindValue(':correo',$this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$consulta->execute();		

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function traerTodos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from usuarios");
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");	
    }

    public static function verificarLogin($correo,$clave){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from usuarios WHERE correo = :correo AND clave = :clave");
        $consulta->bindValue(':correo',$correo, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
		$consulta->execute();
	
		return $consulta->rowcount()>0;
    }

    public static function TraerUnUsuario($correo, $clave)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");
            
        $consulta->bindValue(":correo", $correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $clave, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
    }

    public static function traerUnUsuarioPorCorreo($correo)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE correo = :correo");
            
        $consulta->bindValue(":correo", $correo, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
    }

    public static function TraerUnUsuarioSinClave($correo, $clave)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");
            
        $consulta->bindValue(":correo", $correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $clave, PDO::PARAM_INT);

        $consulta->execute();


        return $consulta->fetchObject('Usuario');
    }

    public function EliminarUsuarioBD($id){
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        if ($consulta->rowCount() > 0) {
            $retorno = true;
        }
        return $retorno;
    }

    public function ModificarUsuarioBD($id,$usuario){
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET correo=:correo, 
        clave=:clave, nombre=:nombre, apellido=:apellido, perfil=:perfil, foto=:foto WHERE id=:id");
                                                    
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
        $consulta->bindValue(':correo',$this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $retorno = true;
        }
        return $retorno;
    }
}