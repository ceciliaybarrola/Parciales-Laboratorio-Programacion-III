<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once 'accesodatos.php';
require_once 'autentificadoraJWT.php';

class Usuario{
    public $id;
    public $correo;
    public $clave;
    public $nombre;
    public $apellido;
    public $id_perfil;
    public $foto;

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
        $usuario->id_perfil = $parametro->id_perfil;
        $usuario->foto = "";

        $destino = __DIR__ . "/../fotos/";
        $archivos = $request->getUploadedFiles();
        if(($id = $usuario->InsertarUsuario()) !== false)
        {
            $nombreAnterior = $archivos['foto']->getClientFilename();
            $extension = explode(".", $nombreAnterior);

            $extension = array_reverse($extension);

            $archivos['foto']->moveTo($destino . $id . "_" . $usuario->apellido . "." . $extension[0]);
            $usuario->foto = $id . "_" . $usuario->apellido . "." . $extension[0];
		
            Usuario::ActualizarBDFoto($usuario->foto, $id);

            $std->exito = true;
            $std->mensaje = "Agregado el usuario exitosamente";
            $std->status = 200;
        }
        $response->withStatus($std->status);
        $response->getBody()->write(json_encode($std));
        return $response;
    }

    public static function VerificarJWT(Request $request,Response $response, $args)
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = 'No se envio token';
        $retorno->status = 403;

        
        $jwt = $request->getHeader('token');
        if(isset($jwt[0]))
        {
            $retorno = AutentificadoraJWT::ValidarJWT($jwt[0]);
        }

        $newResponse = $response->withStatus($retorno->status);
        $newResponse->getBody()->write(json_encode($retorno));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }


    public function mostrarListadoUsuarios(Request $request, Response $response, array $args): Response 
    {
        $std = new stdClass();
        
        $std->exito = false;
        $std->mensaje = "no se ha mostrado con exito el listado";
        $std->dato = '';
        $std->status = 424;
  
        if(($listaUsuarios = Usuario::traerTodos()) !== false)
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


    public function loginUsuario(Request $request, Response $response, array $args): Response 
    {
        $std = new stdClass();
        $std->exito = false;
        $std->usuario = null;
        $std->status = 403;

        $std->jwt = null;
        
        $arrayDeParametros = $request->getParsedBody();
        $datos = json_decode($arrayDeParametros['user']);

        //$datos = json_decode($request->getParsedBody()['user']);

        if(Usuario::verificarLogin($datos->correo,$datos->clave))
        {
            $std->exito = true;
            
            $parametro =  Usuario::TraerUnUsuario($datos->correo,$datos->clave);
            $objSinClave = new stdClass();
            $objSinClave->id=$parametro->id;
            $objSinClave->correo=$parametro->correo;
            $objSinClave->nombre=$parametro->nombre;
            $objSinClave->apellido=$parametro->apellido;
            $objSinClave->id_perfil=$parametro->id_perfil;
            $objSinClave->foto=$parametro->foto;

            $std->usuario = $objSinClave;
            $std->status = 200;

            $std->jwt = AutentificadoraJWT::CrearJWT($parametro);

        }
        $newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    
    }

    public static function EliminarUsuario(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha eliminado el usuario";
        $std->status = 418;

        $id = json_decode($request->getBody())->id_usuario;

        $token = $request->getHeader('token')[0];
        $verificarToken = autentificadoraJWT::ObtenerPayLoad($token);

        $usuario = new Usuario();
        if($verificarToken->exito){
            if($usuario->EliminarUsuarioBD($id)){
                $std->exito = true;
                $std->mensaje = "se ha eliminado el usuario con exito";
                $std->status = 200;
            }
        }
		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUsuario(Request $request, Response $response, array $args): Response{
        $std = new stdClass();
        $std->exito = false;
        $std->mensaje = "no se ha modificado el usuario";
        $std->status = 418;
        
        $arrayDeParametros = json_decode($request->getParsedBody()['usuario']);

        $token = $request->getHeader('token')[0];
        $verificarToken = autentificadoraJWT::ObtenerPayLoad($token);

        $usuario = new Usuario();
        $usuario->id = $arrayDeParametros->id;
        $usuario->correo = $arrayDeParametros->correo;
        $usuario->clave = $arrayDeParametros->clave;
        $usuario->nombre = $arrayDeParametros->nombre;
        $usuario->apellido = $arrayDeParametros->apellido;
        $usuario->id_perfil = $arrayDeParametros->id_perfil;

        $archivos = $request->getUploadedFiles();
        $nombreAnterior = $archivos['foto']->getClientFilename();
        $extension = explode(".", $nombreAnterior);
        $extension = array_reverse($extension);
        $usuario->foto = $usuario->id . "_" . $usuario->apellido . "." . $extension[0]; 
 
        $destino = __DIR__ . "/../fotos/";
        $fotoAnterior = self::TraerUnUsuarioPorID($usuario->id)->foto;

        if($verificarToken->exito)
        {
            if($usuario->ModificarUsuarioBD($usuario->id))
            {
                if(file_exists($destino.$fotoAnterior))
                {
                    unlink($destino.$fotoAnterior);
                }

                $archivos['foto']->moveTo($destino . $usuario->id . "_" . $usuario->apellido . "." . $extension[0]);

                $std->exito = true;
                $std->mensaje = "se ha modificado el usuario con exito";
                $std->status = 200;
            }
        }
		$newResponse = $response->withStatus($std->status);
        $newResponse->getBody()->write(json_encode($std));
        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public static function MostrarTodosPdf(Request $request, Response $response, array $args){
        $token = $request->getHeader("token")[0];
        $payload = NULL;
        $listadoUsuarios = null;
        try {
            $payload = AutentificadoraJWT::ObtenerPayLoad($token); 

        }catch (Exception $e) { 
            die();
        }

        $mpdf = new \Mpdf\Mpdf(['orientation' => 'P', 'nbpgPrefix' => ' / ']);
        
        $mpdf->setHeader(ucfirst("Ybarrola Cecilia") . "||{PAGENO}{nbpg}");
        $fecha = getDate();
        $mpdf->setFooter("<p style='text-align: center;'>$fecha[mday]/$fecha[mon]/$fecha[year]</p>");
        
        $mpdf->WriteHTML("<br><h2 style='text-align: center;'>LISTADO DE USUARIOS:</h2>");
        $grilla = '<table class="table" border="1" align="center">
                    <thead>
                        <tr>
                            <th>CORREO</th>
                            <th>CLAVE</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>FOTO</th>
                            <th>IDPERFIL</th>
                        </tr> 
                    </thead>';
        $listadoUsuarios = Usuario::traerTodos();   	
        foreach ($listadoUsuarios as $usuario){
            $grilla .= "<tr>
                            <td>".$usuario->correo."</td>
                            <td>".$usuario->clave."</td>
                            <td>".$usuario->nombre."</td>
                            <td>".$usuario->apellido."</td>
                            <td><img src='../scr/fotos/" .$usuario->foto."' width='50px' height='50px'/></td>
                            <td>".$usuario->id_perfil."</td>
                        </tr>";
        }
        $grilla .= '</table>';
        $mpdf->WriteHTML("<p>.$grilla</p>");

        $mpdf->Output('mi_pdf.pdf', 'I');
    }

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
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (correo,clave,nombre,apellido,id_perfil,foto)
                                                        values(:correo,:clave,:nombre,:apellido,:id_perfil,:foto)");
		$consulta->bindValue(':correo',$this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
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

    public static function traerUnUsuarioPorCorreo($correo)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE correo = :correo");
            
        $consulta->bindValue(":correo", $correo, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
    }

    public static function TraerUnUsuario($correo, $clave)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");
            
        $consulta->bindValue(":correo", $correo, PDO::PARAM_STR);
        $consulta->bindValue(":clave", $clave, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function TraerUnUsuarioPorID($id)
    {
        $AccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $AccesoDatos->RetornarConsulta("SELECT * FROM usuarios WHERE id = :id");
            
        $consulta->bindValue(":id", $id, PDO::PARAM_STR);

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

    public function ModificarUsuarioBD($id){
        $retorno = false;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET correo=:correo, 
        clave=:clave, nombre=:nombre, apellido=:apellido, id_perfil=:id_perfil, foto=:foto WHERE id=:id");
                                                    
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    
        $consulta->bindValue(':correo',$this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido, PDO::PARAM_STR);
		$consulta->bindValue(':id_perfil', $this->id_perfil, PDO::PARAM_INT);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            $retorno = true;
        }
        return $retorno;
    }
}