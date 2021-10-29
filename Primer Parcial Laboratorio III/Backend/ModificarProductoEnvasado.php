<?php
require_once "./clases/ProductoEnvasado.php";

$producto_json = isset($_POST['producto_json']) ? json_decode($_POST['producto_json']) : false;

$ret = new stdClass();
$ret->exito = false;
$ret->mensaje = "No se recibieron los parametros requieridos";

if($producto_json != false)
{
    $prod = new ProductoEnvasado($producto_json->id, $producto_json->nombre, $producto_json->origen, $producto_json->codigoBarra, $producto_json->precio);
    if($prod->Modificar())
    {
        $ret->exito = true;
        $ret->mensaje= "Producto modificado";
    }
    else{
        $ret->mensaje = "No se pudo modificar el producto";
    }
}

echo json_encode($ret);
?>