<?php
    require_once "./clases/ProductoEnvasado.php";

    echo json_encode(ProductoEnvasado::MostrarBorradosJSON());
?>