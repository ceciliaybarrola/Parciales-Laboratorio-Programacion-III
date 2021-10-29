<?php
    require_once "./clases/ProductoEnvasado.php";

    $producto_json = isset($_POST['producto_json']) ? json_decode($_POST['producto_json']) : false;
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

    $ret = new stdClass();
    $ret->exito = false;
    $ret->mensaje = "No se recibieron los parametros necesarios";

    if($producto_json != false && $foto != false)
    {
        $pathFoto = ProductoEnvasado::GuardarFoto($producto_json->nombre, $producto_json->origen, $foto);
        $producto = new ProductoEnvasado($producto_json->id, $producto_json->nombre, $producto_json->origen, $producto_json->codigoBarra, $producto_json->precio, $pathFoto);
        
        $productoArray = ProductoEnvasado::Traer();

        if($producto->ExisteId($productoArray))
        { 
            if($producto->Modificar())
            {
                foreach($productoArray as $prodAux)
                {
                    if($prodAux->id == $producto->id)
                    {
                        if (file_exists($prodAux->pathFoto)) {
                            $aux = explode("/", $prodAux->pathFoto);
                            rename(chop($prodAux->pathFoto), chop("./productosModificados/".$aux[3]));
                        }
                        break;
                    }
                }
                $ret->exito = true;
                $ret->mensaje = "Producto modificado con exito";
            }
            else
            {
                $ret->mensaje = "No se pudo modificar el producto";
                if(file_exists($pathFoto))
                    unlink($pathFoto);
            }
        }
        else
        {
            $ret->mensaje = "El producto no existe en la base de datos";
            if(file_exists($pathFoto))
                unlink($pathFoto);
        }
    }

    echo json_encode($ret);
    ?>