<?php
require_once "./clases/Producto.php";
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
$origen = isset($_POST['origen']) ? $_POST['origen'] : false;

$ret= new stdClass();
$ret->exito= false;
$ret->mensaje= "No se recibieron los datos";

if($nombre != false && $origen != false)
{
    $producto = new Producto($nombre, $origen);
    $ret= json_decode($producto->GuardarJSON("./archivos/productos.json"));
}
echo json_encode($ret);
?>