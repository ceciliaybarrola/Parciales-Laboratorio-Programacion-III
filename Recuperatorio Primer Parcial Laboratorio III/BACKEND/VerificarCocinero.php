<?php
require "./clases/Cocinero.php";

$email = $_POST["email"] ?? '';
$clave = $_POST["clave"] ?? '';
$aux = new Cocinero("", $email, $clave);
$validar = Cocinero::verificarExistencia($aux);
$resp = new stdClass();
if ($validar->existe) {
    $resp->exito = true;
    $cookieNombre = $aux->_getEmail() . "_" . $aux->_getespecialidad();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $date = date("d-m-Y") . "-" . date("H:i:s");
    setcookie($cookieNombre, $date . " " . $validar->mensaje);

    $resp->mensaje = "Encontrado\n" . $validar->mensaje;
} else {
    $resp->exito = false;
    $resp->mensaje = "No se encontro, ". $validar->mensaje;
    $resp->masPopulares=  $validar->masPopulares;
}
echo json_encode($resp);
