<?php
include_once "../librerias/lib_html.php";
include_once "../librerias/lib_usuarios.php";

$accion = $_GET["accion"];

if ($accion == "login-html") {
    Login_html();
}
if ($accion == "login") {
    Login();
}
?>