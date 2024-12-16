<?php
include_once "../librerias/lib_html.php";
include_once "../librerias/lib_dispositivos.php";

$accion = $_GET["accion"];

if ($accion== "verequipos") {
    Mostrarequipos();
}
if ($accion == "aggequipos") {
    ingresar_equipos();
}
if ($accion == "registrar") {
    RegistrarEquipos();
}
if ($accion == "modificar") {
    Modificar_equipos();
}
if ($accion == "actualizar") {
    Actualizar_equipos();
}
if ($accion == "eliminar") {
    Eliminar();
}

//ingresar_equipos();
?>