<?php

function Conexion(){


$host = "localhost";
$port = "5432";
$dbname = "inventario-smart";
$user = "postgres";
$contraseña ="postgres";

$conexion = pg_connect("dbname = $dbname port =  $port host = $host user = $user password = $contraseña ");

// if ($conexion) {
//     echo "conexion exitosa";
// }
// else {
//     echo "ocurrio un error" .$conexion;
// }
return $conexion;
}
?>