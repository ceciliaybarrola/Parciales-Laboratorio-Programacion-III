<?php
    require_once "./clases/ProductoEnvasado.php";

    $obj_producto = isset($_POST['obj_producto']) ? json_decode($_POST['obj_producto']) : false;
    $ret = "{}";

    if($obj_producto != false)
    {
        $prod = new ProductoEnvasado(null, $obj_producto->nombre, $obj_producto->origen, null, null, null);
        if(($productosArray = ProductoEnvasado::Traer()) != false)
        {
            if($prod->Existe($productosArray))
            {
                foreach($productosArray as $producto)
                {
                    if($producto->nombre == $prod->nombre && $producto->origen == $prod->origen)
                    {
                        $ret = $producto->ToJSON();
                    }
                }
            }
        }
    }

    echo $ret;
?>