<?php
require_once "./clases/ProductoEnvasado.php";

$producto_json = isset($_POST['producto_json']) ? $_POST['producto_json'] : false;
$ret = new stdClass();
$ret->exito = false;
$ret->mensaje = "No se recibieron los parametros necesarios";

if($producto_json != false)
{
    $datos = json_decode($producto_json);
    $producto = new ProductoEnvasado($datos->id, $datos->nombre, $datos->origen);

    if(ProductoEnvasado::Eliminar($producto->id)){

        $ret->exito = true;
        $aux = json_decode($producto->GuardarJSON("./archivos/productos_eliminados.json"));
        if($aux->exito = true)
        {
            $ret->mensaje = "Producto eliminado con exito";
        }
        else
        {
            $ret->mensaje = "Producto eliminado con exito, pero no se pudo escribir en el archivo productos_eliminados"; 
        }
        
    }
    else{
        $ret->mensaje = "No se pudo eliminar el producto";
    }
}

echo json_encode($ret);
?>