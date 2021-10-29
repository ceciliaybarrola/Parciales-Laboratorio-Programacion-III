<?php
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : false;
$origen = isset($_GET['origen']) ? $_GET['origen'] : false;

$ret = new stdClass();
$ret->exito = false;
$ret->mensaje = "No existe una cookie con ese nombre";

if(isset($_COOKIE[$nombre . "_" . $origen])){
    $ret->exito = true;
    $ret->mensaje = $_COOKIE[$nombre . "_" . $origen];
}

echo json_encode($ret);
?>