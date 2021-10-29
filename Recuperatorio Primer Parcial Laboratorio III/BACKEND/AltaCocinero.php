<?php
require "./clases/Cocinero.php";
$especialidad=$_POST["especialidad"]??NULL;
$email=$_POST["email"]??NULL;
$clave=$_POST["clave"]??NULL;
$nuevo= new Cocinero($especialidad,$email,$clave);

$json= new stdClass();
$json=$nuevo->GuardarEnArchivo();
echo json_encode($json) ;
