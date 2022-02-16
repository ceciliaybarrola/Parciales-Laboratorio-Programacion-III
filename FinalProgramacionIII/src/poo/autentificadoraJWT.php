<?php
use Firebase\JWT\JWT;

require_once 'usuario.php';

class AutentificadoraJWT{

    private static $secret_key = 'ClaveSuperSecreta@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function CrearJWT($usuario)
    {
        //agarro la fecha
        $time = time();
        self::$aud = self::Aud();

        $token = array(
            'aud' => self::$aud,
        	'iat'=>$time,
            'exp' => $time + 45,
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'correo' => $usuario->correo,
            'id_perfil' => $usuario->id_perfil,
            'foto' => $usuario->foto,
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function ObtenerPayLoad($token)
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->payload = null;
        $retorno->mensaje = '';
        $retorno->status = 403;

        try {
            $retorno->payload = JWT::decode(
                                            $token,
                                            self::$secret_key,
                                            self::$encrypt
                                        );
            $retorno->exito = TRUE;
            $retorno->status = 200;

        } catch (Exception $e) { 
            $retorno->mensaje = $e->getMessage();
        }

        return $retorno;
    }

    public static function ValidarJWT($token)
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = "Token invalido";
        $retorno->status = 403;

        try 
        {
            if( ! isset($token))
            {
                $retorno->mensaje = "Token vacío";
            }
            else
            {   
                $decode = JWT::decode(
                    $token,
                    self::$secret_key,
                    self::$encrypt
                );

                if($decode->aud !== self::Aud())
                {
                    throw new Exception("Usuario inválido");
                }
                else
                {
                    $retorno->exito = true;
                    $retorno->mensaje = "Token ok";
                    $retorno->status = 200;
                } 
            }          
        } 
        catch (Exception $e) 
        {
            $retorno->mensaje = "Token inválido - " . $e->getMessage();
        }
    
        return $retorno;
    }

    private static function Aud() : string
    {
        $aud = new stdClass();
        $aud->ip_visitante = "";

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud->ip_visitante = $_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud->ip_visitante = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud->ip_visitante = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud->user_agent = @$_SERVER['HTTP_USER_AGENT'];
        $aud->host_name = gethostname();
        
        return json_encode($aud);
    }
}