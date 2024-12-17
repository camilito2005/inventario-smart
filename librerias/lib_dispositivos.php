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
    $observacion = filter_input(INPUT_POST, 'observacion', FILTER_SANITIZE_STRING);
    $contraseña = filter_input(INPUT_POST, 'contraseña', FILTER_SANITIZE_STRING);

    // Validar que todos los campos estén completos
    if (empty($nombre) || empty($marca) || empty($modelo) || empty($ram) || empty($procesador) || empty($almacenamiento) || empty($perifericos) || empty($contraseña)) {
        echo "Todos los campos son obligatorios.";
        echo '<a href="formulario_registro_equipos.php">Volver</a>';
        exit;
    }

    // Conexión a la base de datos
    include_once "../conexion.php";
    $conexion = Conexion();

    /* Verificar si el equipo ya está registrado (por ejemplo, modelo + marca como identificador único)
    $consultaEquipo = "SELECT count(*) FROM dispositivos WHERE dispositivo_marca = $1 AND dispositivo_modelo = $2";
    $resultadoEquipo = pg_query_params($conexion, $consultaEquipo, array($marca, $modelo));
    $countEquipo = pg_fetch_result($resultadoEquipo, 0, 0);

    if ($countEquipo > 0) {
        echo "El equipo con la marca '$marca' y modelo '$modelo' ya está registrado.";
        echo '<a href="formulario_registro_equipos.php">Volver</a>';
        exit;
    }*/

    // Hash seguro para el campo de periféricos si se necesita proteger
    //$hashPerifericos = password_hash($perifericos, PASSWORD_DEFAULT);

    // Fecha de ingreso
    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');

    // Insertar los datos en la tabla equipos
    $consulta = "INSERT INTO dispositivos ( dispositivo_marca, dispositivo_modelo, dispositivo_ram, dispositivo_procesador, dispositivo_almacenamiento, dispositivo_perifericos,dispositivo_nombre_usuario,dispositivo_direccion_mac, observacion, dispositivo_contraseña,fecha_registro) 
                 VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9,$10,$11)";
    $result = pg_query_params($conexion, $consulta, array($marca, $modelo, $ram, $procesador, $almacenamiento, $perifericos,$nombre, $direccion_mac,$observacion,$contraseña, $fecha));

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
    $observacion = filter_input(INPUT_POST, 'observacion', FILTER_SANITIZE_STRING);
    $contraseña = filter_input(INPUT_POST, 'contraseña', FILTER_SANITIZE_STRING);


    // echo "<br> nombre: ".$nombre."</br>";
    // echo "<br> marca :  ".$marca."</br>";
    // echo "<br> modelo : ".$modelo."</br>";
    // echo "<br>ram : ".$ram."</br>";
    // echo "<br> procesador : ".$procesador."</br>";
    // echo "<br> almacenamiento : ".$almacenamiento."</br>";
    // echo "<br> perifericos  : ".$perifericos."</br>";
    // echo "<br> direccion mac : ".$direccion_mac."</br>";

    //Validar campos obligatorios
     /*if (empty($nombre) || empty($marca) || empty($modelo) || empty($ram) || empty($procesador) || empty($almacenamiento) || $perifericos || $direccion_mac || $observacion || $contraseña) {
         echo "Todos los campos son obligatorios.";
        exit;
     }*/
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
        SET dispositivo_nombre_usuario = $1, dispositivo_marca = $2, dispositivo_modelo = $3, dispositivo_ram = $4, dispositivo_procesador = $5, dispositivo_almacenamiento = $6, dispositivo_perifericos = $7, fecha_modificacion = $8 ,dispositivo_direccion_mac = $9, observacion = $10,dispositivo_contraseña = $11
        WHERE dispositivo_id = $12
SQL;

    // Ejecutar la consulta de manera segura
    $resultado_consulta = pg_query_params(
        $conexion,
        $consulta,
        [$nombre, $marca, $modelo, $ram, $procesador, $almacenamiento, $perifericos, $fecha,$direccion_mac,$observacion,$contraseña, $id]
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
                <div class="mb-3">
                    <label class="form-label">Observacion</label>
                    <input type="text" class="form-control" name="observacion" value="{$usuario->observacion}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="text" class="form-control" name="contraseña" value="{$usuario->dispositivo_contraseña}">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-pen"></i> Modificar
                </button>
            </form>
            
        </div>
    </body>
    <div class="btn-back">
                <button class="btn btn-outline-secondary w-100">
                    <a href="../vistas/equipos.php?accion=verequipos">
                        <i class="fa-solid fa-backward"></i> Regresar
                    </a>
                </button>
            </div>
            <div class="btn-home">
                <button class="btn btn-outline-secondary w-100">
                    <a href="../index.php">
                        <i class="fa-solid fa-house"></i> Inicio
                    </a>
                </button>
            </div>
    </html>
HTML;
}

function Buscar($search) {
    if (!empty($search)) {
        include_once "../conexion.php";
        $conexion = Conexion();

        if (!$conexion) {
            die("Error al conectar con la base de datos");
        }

        $consulta = <<<SQL
SELECT 
    dispositivos.dispositivo_id AS id, 
    dispositivos.dispositivo_marca AS marca, 
    dispositivos.dispositivo_modelo AS modelo, 
    dispositivos.dispositivo_ram AS ram, 
    dispositivos.dispositivo_procesador AS procesador, 
    dispositivos.dispositivo_almacenamiento AS almacenamiento, 
    dispositivos.dispositivo_perifericos AS perifericos, 
    dispositivos.dispositivo_nombre_usuario AS nombre, 
    dispositivos.fecha_registro AS fecha_registro, 
    dispositivos.fecha_modificacion AS fecha_modificacion, 
    dispositivos.dispositivo_direccion_mac AS dir_mac,
    dispositivos.observacion AS observacion,
    dispositivos.dispositivo_contraseña AS contraseña
FROM 
    dispositivos
WHERE 
    dispositivos.dispositivo_nombre_usuario ILIKE $1;
SQL;

$resultado_consulta = pg_query_params($conexion, $consulta, ["%$search%"]);

        if (!$resultado_consulta) {
            die("Error en la consulta");
        }


        $array = [];

        if (pg_num_rows($resultado_consulta) > 0) {
            $fila = pg_fetch_all($resultado_consulta);
            foreach ($fila as $filas) {
                $array[] = [
                    "id"      => $filas["id"],
                    "marca"      => $filas["marca"],
                    "modelo"      => $filas["modelo"],
                    "ram"   => $filas["ram"],
                    "procesador"    => $filas["procesador"],
                    "almacenamiento"   => $filas["almacenamiento"],
                    "perifericos"      => $filas["perifericos"],
                    "nombre"  => $filas["nombre"],
                    "fecha_registro"  => $filas["fecha_registro"],
                    "fecha_modificacion"  => $filas["fecha_modificacion"],
                    "dir_mac"  => $filas["dir_mac"],
                    "observacion"  => $filas["observacion"],
                    "contraseña"  => $filas["contraseña"]
                ];
            }
            echo json_encode($array);
        } else {
            echo json_encode([]); // Retorna un array vacío si no hay resultados
        }
    }
}
 // Incluye la conexión a la base de datos

function GenerarPDF() {

    require_once '../fpdf/fpdf.php'; // Incluye la librería FPDF
include_once "../conexion.php";
    // Conexión a la base de datos
    $conexion = Conexion();
    $consulta = "SELECT * FROM dispositivos ORDER BY dispositivo_id";
    $query = pg_query($conexion, $consulta);
    $equipos = pg_fetch_all($query);

    // Crea una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Encabezado del PDF
    $pdf->Cell(0, 10, 'Reporte de Equipos - Inventario SmartInfo', 0, 1, 'C');
    $pdf->Ln(5); // Salto de línea

    // Encabezado de la tabla
    $pdf->SetFillColor(200, 200, 200); // Color gris claro
    //$pdf->Cell(10, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Nombre', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Marca', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Modelo', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'RAM', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Procesador', 1, 1, 'C', true);
    //$pdf->Cell(25, 10, 'ROM', 1, 1, 'C', true);

    // Datos de la tabla
    $pdf->SetFont('Arial', '', 10);

    if ($equipos) {
        foreach ($equipos as $equipo) {
            //$pdf->Cell(10, 10, $equipo['dispositivo_id'], 1, 0, 'C');
            $pdf->Cell(30, 10, utf8_decode($equipo['dispositivo_nombre_usuario']), 1, 0, 'C');
            $pdf->Cell(30, 10, utf8_decode($equipo['dispositivo_marca']), 1, 0, 'C');
            $pdf->Cell(40, 10, utf8_decode($equipo['dispositivo_modelo']), 1, 0, 'C');
            $pdf->Cell(30, 10, $equipo['dispositivo_ram'], 1, 0, 'C');
            $pdf->Cell(35, 10, utf8_decode($equipo['dispositivo_procesador']), 1, 1, 'C');
            //$pdf->Cell(25, 10, utf8_decode($equipo['dispositivo_almacenamiento']), 1, 1, 'C');
        }
    } else {
        $pdf->Cell(190, 10, 'No hay equipos registrados.', 1, 1, 'C');
    }

    // Salida del PDF
    $pdf->Output('D', 'reporte_equipos.pdf'); // Descarga el archivo
    
}

?>