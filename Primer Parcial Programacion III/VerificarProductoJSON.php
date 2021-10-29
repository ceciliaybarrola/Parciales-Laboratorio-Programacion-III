<?php
require_once "./clases/Producto.php";
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
$origen = isset($_POST['origen']) ? $_POST['origen'] : false;

if($nombre != false && $origen != false)
{
    
    $producto = new Producto($nombre, $origen);
    $retorno = json_decode(Producto::VerificarProductoJSON($producto));

    if($retorno->exito){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $date = date("d-m-Y") . "-" . date("H:i:s");
        setcookie($nombre . "_" . $origen, $date . " " . $retorno->mensaje);

        echo json_encode($retorno);
    }
    else{
        echo json_encode($retorno);
    }
}


?>