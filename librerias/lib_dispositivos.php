<?php
function RegistrarEquipos()
{
    session_start();

    // Validar y sanitizar los datos recibidos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $ram = filter_input(INPUT_POST, 'ram', FILTER_SANITIZE_STRING);
    $procesador = filter_input(INPUT_POST, 'procesador', FILTER_SANITIZE_STRING);
    $almacenamiento = filter_input(INPUT_POST, 'almacenamiento', FILTER_SANITIZE_STRING);
    $perifericos = filter_input(INPUT_POST, 'perifericos', FILTER_SANITIZE_STRING);
    $direccion_mac = filter_input(INPUT_POST, 'dir-mac', FILTER_SANITIZE_STRING);

    // Validar que todos los campos estén completos
    if (empty($nombre) || empty($marca) || empty($modelo) || empty($ram) || empty($procesador) || empty($almacenamiento) || empty($perifericos)) {
        echo "Todos los campos son obligatorios.";
        echo '<a href="formulario_registro_equipos.php">Volver</a>';
        exit;
    }

    // Conexión a la base de datos
    include_once "../conexion.php";
    $conexion = Conexion();

    // Verificar si el equipo ya está registrado (por ejemplo, modelo + marca como identificador único)
    $consultaEquipo = "SELECT count(*) FROM dispositivos WHERE dispositivo_marca = $1 AND dispositivo_modelo = $2";
    $resultadoEquipo = pg_query_params($conexion, $consultaEquipo, array($marca, $modelo));
    $countEquipo = pg_fetch_result($resultadoEquipo, 0, 0);

    if ($countEquipo > 0) {
        echo "El equipo con la marca '$marca' y modelo '$modelo' ya está registrado.";
        echo '<a href="formulario_registro_equipos.php">Volver</a>';
        exit;
    }

    // Hash seguro para el campo de periféricos si se necesita proteger
    //$hashPerifericos = password_hash($perifericos, PASSWORD_DEFAULT);

    // Fecha de ingreso
    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');

    // Insertar los datos en la tabla equipos
    $consulta = "INSERT INTO dispositivos ( dispositivo_marca, dispositivo_modelo, dispositivo_ram, dispositivo_procesador, dispositivo_almacenamiento, dispositivo_perifericos,dispositivo_nombre_usuario,dispositivo_direccion_mac,fecha_registro) 
                 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
    $result = pg_query_params($conexion, $consulta, array($marca, $modelo, $ram, $procesador, $almacenamiento, $perifericos,$nombre, $direccion_mac, $fecha));

    if ($result) {
        echo "<script>alert('Equipo registrado correctamente.');</script>";
        header("Location: ./equipos.php?accion=verequipos");
        exit;
    } else {
        echo "Error al registrar el equipo.";
        echo '<a href="formulario_registro_equipos.php">Volver</a>';
        exit;
    }
}


function modificarEquipos000(){
    $datos = [
        "id" => $_GET["id"],
        "nombre" => $_POST["nombre"],
        "apellido" => $_POST["apellido"],
        "telefono" => $_POST["telefono"],
        "direccion" => $_POST["direccion"],
        "correo" => $_POST["correo"],
        "contraseña" => $_POST["contraseña"],
        "cargo_id" => $_POST["cargo_id"]
    ];

    include_once "../../conexion.php";
    $conexion = Conexion();

    // Validar el formato del correo
    /*if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        echo "El correo no es válido.";
        exit;
    }*/

    /*if (strlen($datos['contraseña']) < 6) {
        echo "La contraseña debe tener al menos 6 caracteres.";
        exit;
    }*/
////, contraseña = $6 ,  $datos['contraseña'],
    $consulta = <<<SQL
        UPDATE usuarios SET nombre = $1, apellido = $2, telefono = $3, direccion = $4, correo = $5, contraseña = $6, cargo_id = $7 WHERE id = $8
SQL;

    // Ejecutar la consulta
    $resultado_consulta = pg_query_params($conexion, $consulta, array($datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['direccion'], $datos['correo'],$datos['contraseña'],$datos['cargo_id'], $datos['id']));

    if ($resultado_consulta) {
        header("Location: ");
        exit; // Es buena práctica usar exit después de redireccionar
    } else {
        echo "Error al realizar la operación.";
    }

}

function Actualizar_equipos() {
    $id = $_GET["id"];
    // Validar que el ID esté presente en la URL
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        echo "ID inválido.";
        exit;
    }

    

    // Validar y sanitizar los datos recibidos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $ram = filter_input(INPUT_POST, 'ram', FILTER_SANITIZE_STRING);
    $procesador = filter_input(INPUT_POST, 'procesador', FILTER_SANITIZE_STRING);
    $almacenamiento = filter_input(INPUT_POST, 'almacenamiento', FILTER_SANITIZE_STRING);
    $perifericos = filter_input(INPUT_POST, 'perifericos', FILTER_SANITIZE_STRING);
    $direccion_mac = filter_input(INPUT_POST, 'dir-mac', FILTER_SANITIZE_STRING);


    // echo "<br> nombre: ".$nombre."</br>";
    // echo "<br> marca :  ".$marca."</br>";
    // echo "<br> modelo : ".$modelo."</br>";
    // echo "<br>ram : ".$ram."</br>";
    // echo "<br> procesador : ".$procesador."</br>";
    // echo "<br> almacenamiento : ".$almacenamiento."</br>";
    // echo "<br> perifericos  : ".$perifericos."</br>";
    // echo "<br> direccion mac : ".$direccion_mac."</br>";

    //Validar campos obligatorios
    // if (empty($nombre) || empty($marca) || empty($modelo) || empty($ram) || empty($procesador) || empty($almacenamiento) || $perifericos || $direccion_mac) {
    //     echo "Todos los campos son obligatorios.";
    //     exit;
    // }
    // // Validar formato del correo
    // if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    //     echo "El correo no es válido.";
    //     exit;
    // }
    // Validar longitud de la contraseña
    // if (strlen($contraseña) < 6) {
    //     echo "La contraseña debe tener al menos 6 caracteres.";
    //     exit;
    // }
    // Cifrar la contraseña (opcional, pero recomendado)
    //$contraseña_cifrada = password_hash($contraseña, PASSWORD_BCRYPT);

    // Incluir la conexión
    include_once "../conexion.php";
    $conexion = Conexion();

    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');

    // Consulta parametrizada segura
    $consulta = <<<SQL
        UPDATE dispositivos
        SET dispositivo_nombre_usuario = $1, dispositivo_marca = $2, dispositivo_modelo = $3, dispositivo_ram = $4, dispositivo_procesador = $5, dispositivo_almacenamiento = $6, dispositivo_perifericos = $7, fecha_modificacion = $8 ,dispositivo_direccion_mac = $9
        WHERE dispositivo_id = $10
SQL;

    // Ejecutar la consulta de manera segura
    $resultado_consulta = pg_query_params(
        $conexion,
        $consulta,
        [$nombre, $marca, $modelo, $ram, $procesador, $almacenamiento, $perifericos, $fecha,$direccion_mac, $id]
    );

    if ($resultado_consulta) {
        // Redirigir al usuario después de una operación exitosa
        header("Location: equipos.php?accion=verequipos");
        exit;
    } else {
        // Manejo de errores
        echo "Error al realizar la operación: " . pg_last_error($conexion);
    }
}

function Eliminar()
{
    include_once "../conexion.php";
    $conexion = Conexion();

    //$id = $_GET["id"];

    if (isset($_GET['id'])) {
        $id = base64_decode($_GET['id']);
        // Valida el id descifrado antes de usarlo en la consulta
    }elseif (!$_GET['id']) {
        echo "no llego el id: ";die();
    }

    $consulta = <<<SQL
        DELETE FROM dispositivos WHERE dispositivo_id = $1
SQL;
    $resultado = pg_query_params($conexion, $consulta,array($id));

    if ($resultado) {
        header("Location: equipos.php?accion=verequipos");
        echo "el registro de id " . $id . " eliminado correctamente";
        exit;
    } else {
        echo "error ";
    }
}


function Modificar_equipos()
{
    include_once "../conexion.php";
    $conexion = Conexion();
    $id = $_GET["id"];
    //echo "id:".$id;

    if (isset($_GET['id'])) {
        $id = base64_decode($_GET['id']);
        //echo "id: ".$id;
        // Valida el id descifrado antes de usarlo en la consulta
    }

    // Consulta para obtener la información del usuario
    $consulta_usuario = "SELECT * FROM dispositivos WHERE dispositivo_id = $1";
    $resultado_usuario = pg_query_params($conexion, $consulta_usuario, array($id));
    $usuario = pg_fetch_object($resultado_usuario);

    $id_encriptado = base64_encode($id);

    // Generamos el HTML del formulario
    echo  <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/d6ecbc133f.js" crossorigin="anonymous"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar equipos</title>
        <style>
            body {
                background-color: #f8f9fa;
            }
            .contenedor {
                margin-top: 50px;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .form-title {
                text-align: center;
                color: #495057;
                margin-bottom: 20px;
            }
            .btn-back, .btn-home {
                margin-top: 15px;
            }
            .btn-back a, .btn-home a {
                text-decoration: none;
                color: inherit;
            }
        </style>
    </head>
    <body>
        <div class="container col-md-6 col-lg-5 contenedor">
            <h3 class="form-title">Modificar Registro de equipos</h3>
            <form action="equipos.php?accion=actualizar&id=$id" method="post">
                <input type="hidden" name="id" value="{$id}">
                <div class="mb-3">
                    <label class="form-label">Identificador</label>
                    <input type="text" class="form-control" disabled  name="id" value="{$id}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control"  name="nombre" value="{$usuario->dispositivo_nombre_usuario}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Marca</label>
                    <input type="text" class="form-control" name="marca" value="{$usuario->dispositivo_marca}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" class="form-control" name="modelo" value="{$usuario->dispositivo_modelo}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Memoria ram</label>
                    <input type="text" class="form-control" name="ram" value="{$usuario->dispositivo_ram}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Procesador</label>
                    <input type="text" class="form-control" name="procesador" value="{$usuario->dispositivo_procesador}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Almacenamiento</label>
                    <input type="text" class="form-control" name="almacenamiento" value="{$usuario->dispositivo_almacenamiento}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Perifericos</label>
                    <input type="text" class="form-control" name="perifericos" value="{$usuario->dispositivo_perifericos}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Direccion mac</label>
                    <input type="text" class="form-control" name="dir-mac" value="{$usuario->dispositivo_direccion_mac}">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-pen"></i> Modificar
                </button>
            </form>
            
        </div>
    </body>
    <div class="btn-back">
                <button class="btn btn-outline-secondary w-100">
                    <a href="../usuarios/usuarios.php?accion=verusuarios">
                        <i class="fa-solid fa-backward"></i> Regresar
                    </a>
                </button>
            </div>
            <div class="btn-home">
                <button class="btn btn-outline-secondary w-100">
                    <a href="../../index.php">
                        <i class="fa-solid fa-house"></i> Inicio
                    </a>
                </button>
            </div>
    </html>
HTML;
}


?>