<?php

function Guardar()
{
    if (!empty($_POST["dni"]) && !empty($_POST["nombre"]) && !empty($_POST["apellido"]) && !empty($_POST["telefono"]) && !empty($_POST["direccion"]) && !empty($_POST["correo"]) && !empty($_POST["contraseña"]) && !empty($_POST["rol"])) {
        $datos = [
            "dni" => $_POST['dni'],
            "nombre" => $_POST["nombre"],
            "apellido" => $_POST["apellido"],
            "telefono" => $_POST["telefono"],
            "direccion" => $_POST["direccion"],
            "correo" => $_POST["correo"],
            "contraseña" => $_POST["contraseña"],
            "confirmar_contraseña" => $_POST["confirmar_contraseña"],
            "rol" => $_POST["rol"],

        ];

        include_once "../conexion.php";
        $conexion = Conexion();

        $dni = $datos['dni'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];
        $telefono = $datos['telefono'];
        $direccion = $datos['direccion'];
        $correo = $datos['correo'];
        $contraseña = $datos['contraseña'];
        $comfirm_contraseña = $datos['confirmar_contraseña'];
        $rol = $datos['rol'];
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d g:i:s');

        if ($contraseña !== $comfirm_contraseña) {
            echo "Las contraseñas no coinciden. Por favor, intente de nuevo.";
            echo '<a href="formulario_registro.php?accion=aggusuarios">volver</a>';
            exit;
        }

        if (strlen($contraseña) < 6) {
            echo "La contraseña debe tener al menos 6 caracteres.";
            echo '<a href="formulario_registro.php?accion=aggusuarios">volver</a>';
            exit;
        }
        
    $consultaCorreo = "SELECT count(*) FROM usuarios WHERE correo = $1";

    $resultadoCorreo = pg_query_params($conexion, $consultaCorreo, array($correo));
    $countCorreo = pg_fetch_result($resultadoCorreo, 0, 0);
    if ($countCorreo > 0) {
        echo "El correo '$correo' ya existe";
        exit;
    }

    $consultadni = <<<SQL
            SELECT count(*) FROM usuarios WHERE dni = $1
SQL;
        $resultadoDni = pg_query_params($conexion, $consultadni, array($dni));
        //$countDni = pg_fetch_result($resultadoDni, 0, 0);
        $countDni = pg_fetch_result($resultadoDni, 0, 0);
        if ($countDni > 0) {
            echo "El DNI '$dni' ya existe";
            echo '<a href="formulario_registro.php?accion=aggusuarios">volver</a>';
            exit;
        }

        $consulta = "INSERT INTO usuarios (dni, nombre, apellido, telefono, direccion, correo, contraseña,fecha_ingreso,rol_id) VALUES ($1, $2, $3, $4, $5, $6, $7,$8 , $9)";
        $resultadoc = pg_query_params($conexion, $consulta, array($dni, $nombre, $apellido, $telefono, $direccion, $correo, $contraseña ,$fecha, $rol));

        if ($resultadoc) {
            //header("Location: ./usuarios.php?accion=verusuarios");
            echo "usuario registrado correctamente";
            exit;
        } else {
            if (!$resultadoc) {
                echo "error";
            }
        }
    } elseif(empty($_POST["dni"]) && empty($_POST["nombre"]) && empty($_POST["apellido"]) && empty($_POST["telefono"]) && empty($_POST["direccion"]) && empty($_POST["correo"]) && empty($_POST["contraseña"]) && empty($_POST["rol"])) {
        echo "campos vacios, porfavor llene los campos";
        echo '<a href="formulario_registro.php?accion=aggusuarios">volver</a>';
    }
}

function Actualizar_usuarios(){



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

    include_once "../conexion.php";
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
        UPDATE usuarios SET nombre = $1, apellido = $2, telefono = $3, direccion = $4, correo = $5, contraseña = $6, rol_id = $7 WHERE id = $8
SQL;

    // Ejecutar la consulta
    $resultado_consulta = pg_query_params($conexion, $consulta, array($datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['direccion'], $datos['correo'],$datos['contraseña'],$datos['cargo_id'], $datos['id']));

    if ($resultado_consulta) {
        header("Location: usuarios.php?accion=ver");
        exit; // Es buena práctica usar exit después de redireccionar
    } else {
        echo "Error al realizar la operación.";
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
        DELETE FROM usuarios WHERE id = $1
SQL;
    $resultado = pg_query_params($conexion, $consulta,array($id));

    if ($resultado) {
        header("Location: usuarios.php?accion=ver");
        echo "el registro de id " . $id . " eliminado correctamente";
        exit;
    } else {
        echo "error ";
    }
}
function Modificar_usuarios()
{
    include_once "../conexion.php";
    $conexion = Conexion();
    $id = $_GET["id"];

    if (isset($_GET['id'])) {
        $id = base64_decode($_GET['id']);
        // Valida el id descifrado antes de usarlo en la consulta
    }

    // Consulta para obtener la información del usuario
    $consulta_usuario = "SELECT * FROM usuarios WHERE id = $1";
    $resultado_usuario = pg_query_params($conexion, $consulta_usuario, array($id));
    $usuario = pg_fetch_object($resultado_usuario);

    // Consulta para obtener todos los cargos disponibles
    $consulta_cargos = "SELECT id, descripcion FROM roles";
    $resultado_cargos = pg_query($conexion, $consulta_cargos);
    $cargos = pg_fetch_all($resultado_cargos); // Convertimos a array para usar foreach

    // Generamos el HTML del formulario
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/d6ecbc133f.js" crossorigin="anonymous"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar Registro</title>
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
            <h3 class="form-title">Modificar Registro de Usuario</h3>
            <form action="usuarios.php?accion=actualizar&id=$id" method="post">
                <input type="hidden" name="id" value="{$id}">
                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" class="form-control" disabled name="dni" value="{$usuario->dni}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="nombre" value="{$usuario->nombre}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellido" value="{$usuario->apellido}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="number" class="form-control" name="telefono" value="{$usuario->telefono}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="direccion" value="{$usuario->direccion}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" class="form-control" name="correo" value="{$usuario->correo}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="text" class="form-control" name="contraseña" value="{$usuario->contraseña}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Cargo</label>
                    <select class="form-control" name="cargo_id">
HTML;

    // Rellenar el selector de cargos usando foreach
    foreach ($cargos as $cargo) {
        $selected = ($cargo['id'] == $usuario->rol_id) ? "selected" : "";
        $html .= "<option value=\"{$cargo['id']}\" {$selected}>{$cargo['descripcion']}</option>";
    }

    $html .= <<<HTML
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-pen"></i> Modificar
                </button>
            </form>
            
        </div>
    </body>
    <div class="btn-back">
                <button class="btn btn-outline-secondary w-100">
                    <a href="usuarios.php?accion=verusuarios">
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

    echo $html;
}

?>