<?php
require_once "../librerias/lib_html.php";
require_once "../librerias/lib_estadisticas.php";

$accion = $_GET["accion"];

if ($accion == "masmarcas") {
    EquiposPorMarca();
}
if ($accion == "registrar") {
    
}

?>