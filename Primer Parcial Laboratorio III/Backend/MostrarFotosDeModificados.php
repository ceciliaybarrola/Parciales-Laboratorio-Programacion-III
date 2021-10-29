<?php
    require_once "./clases/ProductoEnvasado.php";

    $tabla = "<table>
                <thead>
                    <tr>
                        <h3>Imagenes Modificadas</h3>
                    </tr>
                </thead>
                <tbody>";

                $arrayImg = ProductoEnvasado::MostrarModificados();
                foreach($arrayImg as $img)
                {
                    $tabla.="<tr>".$img."</tr></br>";
                }
    $tabla .=   "</tbody>
                </table>";

    echo $tabla;
?>