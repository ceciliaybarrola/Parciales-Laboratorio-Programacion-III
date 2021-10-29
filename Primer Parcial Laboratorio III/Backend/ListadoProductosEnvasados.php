<?php
require_once "./clases/ProductoEnvasado.php";
$aux = isset($_GET) ? true : false;

if($aux){
    $accion = isset($_GET['tabla']) ? $_GET['tabla'] : 'No se paso tabla';

    if(($prodArray = ProductoEnvasado::Traer()) != false)
    {
        switch($accion)
        {
            case 'mostrar':
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

                        foreach($prodArray as $prod)
                        {
                            $tabla.="<tr><td>$prod->id</td><td>$prod->nombre</td><td>$prod->origen</td><td>$prod->codigoBarra</td><td>$prod->precio</td><td><img src=$prod->pathFoto width=50 height=50 /></td><tr></tr>";
                        }
                $tabla .=   "</tbody>
                        </table>";

                echo $tabla;

                break;
            default:
            
            echo json_encode($prodArray);
            break;
        }
    }
}
else{
    echo 'No se recibio parametro GET';
}
?>