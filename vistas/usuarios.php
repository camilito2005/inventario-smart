<?php

include_once "../librerias/lib_html.php";
include_once "../librerias/lib_usuarios.php";

$accion = $_GET["accion"];

if ($accion == "ver") {
    Mostrar_usuarios();
}
if ($accion == "aggusuarios") {
    Formulario_usuarios();
}

if ($accion == "registrar") {
    Guardar();
}
if ($accion == "modificar") {
    Modificar_usuarios();
}
if ($accion == "actualizar") {
    Actualizar_usuarios();
}
if ($accion == "eliminar") {
    Eliminar();
}
if ($accion == "cerrar") {
    Cerrar_sesion();
}
if ($accion == "buscar") {
    $search = $_REQUEST["search"];
    Buscar($search);
}
if ($accion == "perfil") {
    Perfil();
}
?>