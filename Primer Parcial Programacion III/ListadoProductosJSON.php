<?php 
    require_once "./clases/Producto.php";

    if(isset($_GET)){
        $producto_array = Producto::TraerJSON('./archivos/productos.json');
        echo json_encode($producto_array);
    }
    else{
        echo 'No se recibio GET';
    }




?>