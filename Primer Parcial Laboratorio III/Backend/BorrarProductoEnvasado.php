<?php

    require_once "./clases/ProductoEnvasado.php";

    $producto_json = isset($_POST['producto_json']) ? json_decode($_POST['producto_json']) : false;
    $paramGet = isset($_GET) ? true : false;
    $ret = new stdClass();
    $ret->exito = false;
    $ret->mensaje = "No se recibieron los parametros necesarios";

    if($producto_json != false)
    {
        $producto = new ProductoEnvasado($producto_json->id, $producto_json->nombre, $producto_json->origen, $producto_json->codigoBarra, $producto_json->precio, $producto_json->pathFoto);
        if($producto->Existe(ProductoEnvasado::Traer()))
        {
            if(ProductoEnvasado::Eliminar($producto->id))
            {
                $producto->GuardarEnArchivo();

                $ret->exito = true;
                $ret->mensaje = "Se pudo borrar, el producto no tenia foto";

                if($producto->pathFoto != null)
                {
                    $ret->mensaje = "Se pudo borrar, se movio la foto";
                }
            }
            else
            {
                $ret->mensaje = "No se pudo borrar";
            }
        }
        else
        {
            $ret->mensaje = "El producto no estaba en la base de datos";
        }
        
    }
    else if($paramGet != false && $producto_json == false)
    {
        $f = fopen("./archivos/productos_envasados_borrados.txt", "r");
        $arrayProductos = array();

        while(!feof($f))
        {
            $stringAux = fgets($f);
            $stringAux = trim($stringAux);

            if($stringAux != "")
            {
                $arrayAux = explode("-", $stringAux);
                if(isset($arrayAux[5]))
                {
                   array_push($arrayProductos, new ProductoEnvasado($arrayAux[0], $arrayAux[1], $arrayAux[2], $arrayAux[3], $arrayAux[4], $arrayAux[5])); 
                }
                else
                {
                    array_push($arrayProductos, new ProductoEnvasado($arrayAux[0], $arrayAux[1], $arrayAux[2], $arrayAux[3], $arrayAux[4], null)); 
                }
            }
        }

        fclose($f);

        $tabla = "<table border=1>
                                <thead>
                                    <tr>
                                        <td>Id</td>
                                        <td>Nombre</td>
                                        <td>Origen</td>
                                        <td>CodigoBarra</td>
                                        <td>Precio</td>
                                        <td>Foto</td>
                                    </tr>
                                </thead>
                                <tbody>";
    
                                foreach($arrayProductos as $prod)
                                {
                                    $tabla.="<tr><td>$prod->id</td><td>$prod->nombre</td><td>$prod->origen</td><td>$prod->codigoBarra</td><td>$prod->precio</td><td><img src=$prod->pathFoto width=50 height=50 /></td><tr></tr>";
                                }
                        $tabla .=   "</tbody>
                              </table>";

        echo $tabla;
        die();
    }

    echo json_encode($ret);