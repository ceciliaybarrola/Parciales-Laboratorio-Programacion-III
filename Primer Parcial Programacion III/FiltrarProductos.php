<?php

    require_once "./clases/ProductoEnvasado.php";

    $origen = isset($_POST['origen']) ? $_POST['origen'] : false;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;

    $flag = false;
    $tabla = "<table>
                <thead>
                    <tr>
                        <h3>Productos Filtrados</h3>
                    </tr>
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

    if($origen != false && $nombre == false)
    {
        if(($productosTodos = ProductoEnvasado::Traer()) != false)
        {
            foreach($productosTodos as $producto)
            {
                if($producto->origen == $origen)
                {
                    $flag = true;
                    $tabla.="<tr><td>$producto->id</td><td>$producto->nombre</td><td>$producto->origen</td><td>$producto->codigoBarra</td><td>$producto->precio</td><td><img src=$producto->pathFoto width=50 height=50 /></td><tr></tr>";
                }
            }
            $tabla .=   "</tbody>
            </table>";
            if($flag)
            {
               echo $tabla; 
            }
            else{
                echo "Ningun producto en la base de datos coincidia con los parametros";
            }
        }
        else
        {
            echo "No se pudo traer la base de datos";
        }
    }
    else if($origen == false && $nombre != false)
    {
        if(($productosTodos = ProductoEnvasado::Traer()) != false)
        {
            foreach($productosTodos as $producto)
            {
                if($producto->nombre == $nombre)
                {
                    $flag = true;
                    $tabla.="<tr><td>$producto->id</td><td>$producto->nombre</td><td>$producto->origen</td><td>$producto->codigoBarra</td><td>$producto->precio</td><td><img src=$producto->pathFoto width=50 height=50 /></td><tr></tr>";
                }
            }
            $tabla .=   "</tbody>
            </table>";
            if($flag)
            {
               echo $tabla; 
            }
            else{
                echo "Ningun producto en la base de datos coincidia con los parametros";
            }
        }
        else
        {
            echo "No se pudo traer la base de datos";
        }
    }
    else if($origen != false && $nombre != false)
    {
        if(($productosTodos = ProductoEnvasado::Traer()) != false)
        {
            foreach($productosTodos as $producto)
            {
                if($producto->nombre == $nombre && $producto->origen == $origen)
                {
                    $flag = true;
                    $tabla.="<tr><td>$producto->id</td><td>$producto->nombre</td><td>$producto->origen</td><td>$producto->codigoBarra</td><td>$producto->precio</td><td><img src=$producto->pathFoto width=50 height=50 /></td><tr></tr>";
                }
            }
            $tabla .=   "</tbody>
            </table>";

            if($flag)
            {
               echo $tabla; 
            }
            else{
                echo "Ningun producto en la base de datos coincidia con los parametros";
            }
            
        }
        else
        {
            echo "No se pudo traer la base de datos";
        }
    }
    else
    {
        echo "No se recibieron los parametros requieridos";
    }
?>