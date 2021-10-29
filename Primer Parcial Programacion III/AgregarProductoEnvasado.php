<?php

    require_once "./clases/ProductoEnvasado.php";

    $codigoBarra = isset($_POST['codigoBarra']) ? $_POST['codigoBarra'] : false;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
    $origen = isset($_POST['origen']) ? $_POST['origen'] : false;
    $precio = isset($_POST['precio']) ? $_POST['precio'] : false;
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

    $ret = new stdClass();
    $ret->exito = false;
    $ret->mensaje = "No se recibieron los parametros requeridos";

    if($codigoBarra != false && $nombre != false && $origen != false && $precio != false && $foto != false)
    {
        $pathFoto = ProductoEnvasado::GuardarFoto($nombre, $origen, $foto);
        $prod = new ProductoEnvasado(null, $nombre, $origen, $codigoBarra, $precio, $pathFoto);

        if(!$prod->Existe(ProductoEnvasado::Traer()))
        {
            if($prod->Agregar())
            {
                $ret->exito = true;
                $ret->mensaje = "Se guardo el producto";
            }
            else
            {
                $ret->mensaje = "No se pudo agregar el producto";
            }
        }
        else
        {
            $ret->mensaje = "Ya existe el producto en la base de datos";
        }
    }

    echo json_encode($ret);
    ?>   