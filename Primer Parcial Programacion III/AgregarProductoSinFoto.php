<?php
    require_once "./clases/ProductoEnvasado.php";

    $producto_json = isset($_POST['producto_json']) ? $_POST['producto_json'] : false;

    $ret = new stdClass();
    $ret->exito = false;
    $ret->mensaje = "No se recibieron parametros necesarios";

    if($producto_json != false)
    {
        $datos = json_decode($producto_json);
        $producto = new ProductoEnvasado(0, $datos->nombre, $datos->origen, $datos->codigoBarra, $datos->precio, null);
        
        if($producto->Agregar())
        {
            $ret->exito = true;
            $ret->mensaje = "Producto agregado con exito";
        }
        else
        {
            $ret->mensaje = "Producto no se pudo agregar";
        }
    }

    echo json_encode($ret);
    ?>