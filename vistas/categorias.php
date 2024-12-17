<?php
require_once "../librerias/lib_html.php";
require_once "../librerias/lib_categorias.php";

$accion = $_GET["accion"];

if ($accion == "vercategorias") {
    MostrarCategorias();
}
if ($accion == "aggcategorias") {
    Formulario_categorias();
}
if ($accion == "registrar") {
    Ingresar_categorias();
}
if ($accion == "modificar-html") {
    Modificar_categorias();
}
if ($accion == "actualizar") {
    Actualizar_equipos();
}
if ($accion == "eliminar") {
    Eliminar();
}

?>