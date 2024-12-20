<?php

function Guardar000()
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
            header("Location: ./usuarios.php?accion=ver");
            //echo "usuario registrado correctamente";
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


function Guardar()
{
    
    if (
        !empty($_POST["dni"]) &&
        !empty($_POST["nombre"]) &&
        !empty($_POST["apellido"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["direccion"]) &&
        !empty($_POST["correo"]) &&
        !empty($_POST["contraseña"]) &&
        !empty($_POST["rol"]) &&
        !empty($_POST["confirmar_contraseña"])
    ) {
        $datos = [
            "dni" => htmlspecialchars($_POST['dni']),
            "nombre" => htmlspecialchars($_POST["nombre"]),
            "apellido" => htmlspecialchars($_POST["apellido"]),
            "telefono" => htmlspecialchars($_POST["telefono"]),
            "direccion" => htmlspecialchars($_POST["direccion"]),
            "correo" => filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),
            "contraseña" => $_POST["contraseña"],
            "confirmar_contraseña" => $_POST["confirmar_contraseña"],
            "rol" => htmlspecialchars($_POST["rol"]),
        ];

        include_once "../conexion.php";
        $conexion = Conexion();

        // Validar coincidencia de contraseñas
        if ($datos["contraseña"] !== $datos["confirmar_contraseña"]) {
            header("Location: ./usuarios.php?accion=aggusuarios&mensaje=Las contraseñas no coinciden. Por favor, intente de nuevo.");
            exit;
        }

        // Validar longitud de contraseña
        if (strlen($datos["contraseña"]) < 6) {
            header("Location: ./usuarios.php?accion=aggusuarios&mensaje=La contraseña debe tener al menos 6 caracteres.");
            exit;
        }

        // Verificar existencia de correo
        $consultaCorreo = "SELECT count(*) FROM usuarios WHERE correo = $1";
        $resultadoCorreo = pg_query_params($conexion, $consultaCorreo, [$datos["correo"]]);
        if (pg_fetch_result($resultadoCorreo, 0, 0) > 0) {
            header("Location: ./usuarios.php?accion=aggusuarios&mensaje=El correo ya está registrado. Intenta con otro.");
            exit;
        }

        // Verificar existencia de DNI
        $consultadni = "SELECT count(*) FROM usuarios WHERE dni = $1";
        $resultadoDni = pg_query_params($conexion, $consultadni, [$datos["dni"]]);
        if (pg_fetch_result($resultadoDni, 0, 0) > 0) {
            header("Location: ./usuarios.php?accion=aggusuarios&mensaje=El DNI ya está registrado. Intenta con otro.");
            exit;
        }

        // Hash de la contraseña
        $contraseñaHash = password_hash($datos["contraseña"], PASSWORD_BCRYPT);

        // Insertar usuario
        $consulta = "INSERT INTO usuarios (dni, nombre, apellido, telefono, direccion, correo, contraseña, fecha_ingreso, rol_id) 
                     VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
        $fecha = date('Y-m-d H:i:s');
        $params = [
            $datos["dni"], $datos["nombre"], $datos["apellido"], $datos["telefono"], 
            $datos["direccion"], $datos["correo"], $contraseñaHash, $fecha, $datos["rol"]
        ];
        $resultado = pg_query_params($conexion, $consulta, $params);

        if ($resultado) {
            header("Location: ./usuarios.php?accion=ver");
            exit;
        } else {
            header("Location: ./usuarios.php?accion=aggusuarios&mensaje=Hubo un error al registrar el usuario. Intenta nuevamente.");
            exit;
        }
    } else {
        header("Location: ./usuarios.php?accion=aggusuarios&mensaje=Por favor, completa todos los campos.");
        exit;
    }
}


function Actualizar_usuarios(){

    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        header("Location: usuarios.php?accion=modificar&mensaje=El identificador del usuario es inválido.");
        exit;
    }

    $datos = [
        "id" => htmlspecialchars($_GET["id"]),
        "nombre" => htmlspecialchars($_POST["nombre"]),
        "apellido" => htmlspecialchars($_POST["apellido"]),
        "telefono" => htmlspecialchars($_POST["telefono"]),
        "direccion" => htmlspecialchars($_POST["direccion"]),
        "correo" => filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),
        "contraseña" => $_POST["contraseña"],
        "cargo_id" => htmlspecialchars($_POST["cargo_id"])
    ];

    $contraseña_hash = password_hash($datos['contraseña'], PASSWORD_BCRYPT);

    include_once "../conexion.php";
    $conexion = Conexion();

    // Validar el formato del correo
    if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        header("Location: usuarios.php?accion=modificar&mensaje=El correo no es válido.");
        exit;
    }

    if (strlen($datos['contraseña']) < 6) {
        header("Location: usuarios.php?accion=modificar&mensaje=La contraseña debe tener al menos 6 caracteres.");
        exit;
    }
////, contraseña = $6 ,  $datos['contraseña'],
    $consulta = <<<SQL
        UPDATE usuarios SET nombre = $1, apellido = $2, telefono = $3, direccion = $4, correo = $5, contraseña = $6, rol_id = $7 WHERE id = $8
SQL;

    // Ejecutar la consulta
    $resultado_consulta = pg_query_params($conexion, $consulta, array($datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['direccion'], $datos['correo'],$contraseña_hash,$datos['cargo_id'], $datos['id']));

    if ($resultado_consulta) {
        header("Location: usuarios.php?accion=ver");
        exit; // Es buena práctica usar exit después de redireccionar
    } else {
        header("Location: usuarios.php?accion=modificar&mensaje=Error al realizar la operación.");
        exit; // Es buena práctica usar exit después de redireccionar
    }

}


function Actualizar_usuarios000()
{
    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        echo "El identificador del usuario es inválido.";
        exit;
    }

    $datos = [
        "id" => htmlspecialchars($_GET["id"]),
        "nombre" => htmlspecialchars($_POST["nombre"]),
        "apellido" => htmlspecialchars($_POST["apellido"]),
        "telefono" => htmlspecialchars($_POST["telefono"]),
        "direccion" => htmlspecialchars($_POST["direccion"]),
        "correo" => filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),
        "contraseña" => $_POST["contraseña"],
        "cargo_id" => htmlspecialchars($_POST["cargo_id"])
    ];

    include_once "../conexion.php";
    $conexion = Conexion();

    // Validar el formato del correo
    if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        echo "El correo no es válido.";
        exit;
    }

    // Validar longitud de contraseña si se va a actualizar
    if (!empty($datos['contraseña']) && strlen($datos['contraseña']) < 6) {
        echo "La contraseña debe tener al menos 6 caracteres.";
        exit;
    }

    // Preparar la consulta
    $consulta = <<<SQL
        UPDATE usuarios 
        SET nombre = $1, apellido = $2, telefono = $3, direccion = $4, correo = $5, contraseña = $6, rol_id = $7 
        WHERE id = $8
SQL;

    // Si no se actualiza la contraseña, mantener la existente
    $contraseñaHash = !empty($datos['contraseña']) 
        ? password_hash($datos['contraseña'], PASSWORD_BCRYPT) 
        : null;

    // Recuperar la contraseña actual si no se pasa una nueva
    if (is_null($contraseñaHash)) {
        $consultaContraseña = "SELECT contraseña FROM usuarios WHERE id = $1";
        $resultadoContraseña = pg_query_params($conexion, $consultaContraseña, [$datos['id']]);
        if ($resultadoContraseña) {
            $contraseñaHash = pg_fetch_result($resultadoContraseña, 0, 0);
        } else {
            echo "Error al recuperar la contraseña actual.";
            exit;
        }
    }

    // Ejecutar la consulta de actualización
    $params = [
        $datos['nombre'], 
        $datos['apellido'], 
        $datos['telefono'], 
        $datos['direccion'], 
        $datos['correo'], 
        $contraseñaHash, 
        $datos['cargo_id'], 
        $datos['id']
    ];
    $resultadoConsulta = pg_query_params($conexion, $consulta, $params);

    if ($resultadoConsulta) {
        header("Location: usuarios.php?accion=ver");
        exit; // Es buena práctica usar exit después de redireccionar
    } else {
        echo "Error al actualizar el usuario.";
    }
}


function Eliminar000()
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
        header("Location: usuarios.php?accion=ver&mensaje=error");
        exit;
    }
}

function Eliminar()
{
    include_once "../conexion.php";
    $conexion = Conexion();

    // Verificar si el parámetro 'id' está presente
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: usuarios.php?accion=ver&mensaje=No se proporcionó un ID válido.");
        exit;
    }

    // Decodificar y validar el ID
    $id = base64_decode($_GET['id']);
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        header("Location: usuarios.php?accion=ver&mensaje=El ID proporcionado no es válido.");
        exit;
    }

    // Preparar y ejecutar la consulta
    $consulta = "DELETE FROM usuarios WHERE id = $1";
    $resultado = pg_query_params($conexion, $consulta, [$id]);

    if ($resultado) {
        header("Location: usuarios.php?accion=ver");
        exit;
    } else {
        header("Location: usuarios.php?accion=ver&mensaje=Error al intentar eliminar el registro.");
        exit;
    }
}

function Modificar_usuarios()
{
    $mensaje=$_REQUEST["mensaje"];
    if (empty($mensaje)) {
        $mensaje = "";
    }
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
        <link rel="stylesheet" href="../css/modificar_usuarios.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar Registro</title>
        <style>
            .mensaje{
            text-align: center;
        font-size: 20px;
        color: red;
        }
        #mensaje{
            text-align: center;
        font-size: 20px;
        color: red;
        }
        </style>
    </head>
    <body>
        <p class="mensaje">{$mensaje}</p>
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
                    <a href="usuarios.php?accion=ver">
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


function Login(){
    session_start();

    include_once "../conexion.php";

    $conexion = Conexion();

   
     // Validar que se envíen los datos del formulario
     if (!isset($_POST['correo'], $_POST['contraseña']) || empty(trim($_POST['correo'])) || empty(trim($_POST['contraseña']))) {
        echo "Por favor, completa todos los campos.";
        return;
    }

     // Obtener las credenciales del formulario
     $correo = $_POST["correo"];
     $contraseña = $_POST["contraseña"];



 
    // Consultar la base de datos para validar el usuario
    $consulta = pg_query_params(
        $conexion,
        "SELECT 
            u.id, 
            u.dni, 
            u.nombre, 
            u.apellido, 
            u.telefono, 
            u.direccion,  
            u.correo, 
            u.contraseña, 
            u.rol_id, 
            r.descripcion AS rol_descripcion
         FROM 
            usuarios u
         INNER JOIN 
            roles r 
         ON 
            u.rol_id = r.id
         WHERE 
            u.correo = $1",
        array($correo)
    );
    

    $resultado_consulta = pg_fetch_assoc($consulta);

    $contaseña_delabasededatos = $resultado_consulta['contraseña'];

    $contraseña_veryfy=password_verify($contraseña,$contaseña_delabasededatos);

    // Verifica si se encontró un resultado
    if ($resultado_consulta) {
        // Verificar la contraseña
        if ($contraseña_veryfy) { // Asegúrate de comparar correctamente
            $_SESSION["id"] = $resultado_consulta['id'];
            $_SESSION["dni"] = $resultado_consulta['dni'];
            $_SESSION["nombre"] = $resultado_consulta['nombre'];
            $_SESSION["apellido"] = $resultado_consulta['apellido'];
            $_SESSION["telefono"] = $resultado_consulta['telefono'];
            $_SESSION["direccion"] = $resultado_consulta['direccion'];
            $_SESSION["correo"] = $resultado_consulta['correo'];
            $_SESSION["contraseña"] = $resultado_consulta['contraseña'];
            $_SESSION["descripcion"] = $resultado_consulta['rol_descripcion'];
            $_SESSION["rol_id"] = $resultado_consulta['rol_id']; // Guarda el cargo_id para redirigir

            // Redirecciona según el rol del usuario
            if ($resultado_consulta['rol_id'] == 1) {  // Administrador
                header("Location: ../vistas/usuarios.php?accion=ver"); // Cambia la URL según tu estructura
                /*echo "<br>hola nombre :".$_SESSION["nombre"]."</br>";
                echo "<br> descripcion : ".$_SESSION["descripcion"]."</br>";
                echo "<br> rol : ".$_SESSION["rol_id"];*/
                exit;
            } elseif ($resultado_consulta['rol_id'] == 2) {  // Empleado
                header("Location: ../vistas/usuarios.php?accion=ver"); 
                /*echo "<br>hola nombre :".$_SESSION["nombre"]."</br>";
                echo "<br> descripcion : ".$_SESSION["descripcion"]."</br>";
                echo "<br> rol : ".$_SESSION["rol_id"];*/
                //header("Location: ../catalogo/catalogo.php?accion=catalogo"); // Cambia la URL según tu estructura
                exit;
            } else {
                echo "Rol no reconocido.";
            }
            exit; // Asegúrate de llamar a exit después de redireccionar
        } else {
            header("Location: ../vistas/login.php?accion=login-html&mensaje=correo o Contraseña incorrectas.");
        }
    } else {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=El correo no está registrado.");
    }
}

function Cerrar_sesion()
{
    session_start();
    session_destroy();
    header("Location: ../vistas/login.php?accion=login-html");
    exit;
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
    usuarios.id, 
    usuarios.dni, 
    usuarios.nombre, 
    usuarios.apellido, 
    usuarios.telefono, 
    usuarios.direccion, 
    usuarios.correo, 
    usuarios.contraseña AS contraseña, 
    roles.descripcion AS cargo 
FROM 
    usuarios
JOIN 
    roles 
ON 
    usuarios.rol_id = roles.id
WHERE 
    usuarios.nombre ILIKE $1;
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
                    "dni"      => $filas["dni"],
                    "nombre"      => $filas["nombre"],
                    "apellidos"   => $filas["apellido"],
                    "telefono"    => $filas["telefono"],
                    "direccion"   => $filas["direccion"],
                    "correo"      => $filas["correo"],
                    "contraseña"  => $filas["contraseña"],
                    "cargo"  => $filas["cargo"]
                ];
            }
            echo json_encode($array);
        } else {
            echo json_encode([]); // Retorna un array vacío si no hay resultados
        }
    }
}

function Perfil() {
    require_once "../librerias/lib_html.php";

    session_start();
    include_once "../conexion.php";
    $conexion = Conexion();

    $consulta_cargos = "SELECT id, descripcion FROM roles";
    $resultado_cargos = pg_query($conexion, $consulta_cargos);
    $cargos = pg_fetch_all($resultado_cargos); // Convertimos a array para usar foreach
    
    if (isset($_GET['login_success']) && $_GET['login_success'] == 1) {
        echo "<script>alert('Inicio de sesión exitoso. ¡Bienvenido!');</script>";
    }

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
HTML;

    Menu($inicio="../index.php", $ruta_titulo="../index.php", $titulo = "Inventario SmartInfo", $ruta_perfil="./usuarios.php?accion=perfil", $cerrar="./usuarios.php?accion=cerrar", $login="./login.php?accion=login-html", $aggequipos="./equipos.php?accion=verequipos", $ruta_categorias="./categorias.php?accion=vercategorias", $reportes="./estadisticas.php?accion=masmarcas", $verusuario="./usuarios.php?accion=ver");

    echo <<<HTML
    <div class="container mt-5">
        <h1 class="text-center">Perfil de Usuario</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Usuario</h5>
HTML;

    if (isset($_SESSION["correo"])) {
        echo <<<HTML
            <p><strong>Cargo/Rol: {$_SESSION["descripcion"]}</strong></p>
            <p><strong>Identificador: {$_SESSION["id"]}</strong></p>
            <p><strong>Cedula: {$_SESSION["dni"]}</strong></p>
            <p><strong>Nombre: {$_SESSION["nombre"]}</strong></p>
            <p><strong>Apellido: {$_SESSION["apellido"]}</strong></p>
            <p><strong>Telefono: {$_SESSION["telefono"]}</strong></p>
            <p><strong>Direccion: {$_SESSION["direccion"]}</strong></p>
            <p><strong>Correo: {$_SESSION["correo"]}</strong></p>
            <div class="d-flex justify-content-between mt-3">
                <a class="btn btn-primary" href="#editModal" data-bs-toggle="modal">Editar</a>
                <a href="../usuarios/usuarios.php?accion=cerrar" class="btn btn-danger">Cerrar sesión</a>
            </div>
HTML;
    } else {
        echo <<<HTML
            <p>Para continuar, inicia sesión.</p>
            <a href="../pagina-principal/login.php?accion=login" class="btn btn-primary">Iniciar sesión</a>
HTML;
    }

    echo <<<HTML
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../librerias/lib_configuracion.php?accion=actualizar&id={$id}" method="POST">
                        <div class="mb-3">
                            <label for="identificador" class="form-label">Identificador</label>
                            <input type="text" class="form-control" id="identificador" name="id" value="{$id}" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="dni" name="dni" value="{$dni}" disabled required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{$nombre}" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="{$apellido}" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{$telefono}" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{$direccion}" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="{$correo}" required>
                        </div>
                        <div class="mb-3">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" value="{$contraseña}" required>
                        </div>
                        <div class="mb-3">
                            <label for="cargo" class="form-label">Cargo</label>
                            <select class="form-select" name="cargo_id" required>
HTML;

    foreach ($cargos as $cargo) {
        $id = $cargo['id'];
        $descripcion = $cargo['descripcion'];
        echo "<option value=\"$id\">$descripcion</option>";
    }

    echo <<<HTML
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form action="../index.php" method="post">
        <button class="btn btn-outline-secondary mt-3">
            <i class="fa-solid fa-house"></i> Inicio
        </button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
HTML;
}

function Formulario_enviar_correo() {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../css/enviar_correo.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d6ecbc133f.js" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <style>
    </style>
</head>
HTML;
Menu($inicio="../index.php",$ruta_titulo="../index.php", $titulo = "Inventario SmartInfo", $ruta_perfil="./usuarios.php?accion=perfil",$cerrar="./usuarios.php?accion=cerrar",$login="./login.php?accion=login-html",$aggequipos="./equipos.php?accion=verequipos",$ruta_categorias="./categorias.php?accion=vercategorias",$reportes="./estadisticas.php?accion=masmarcas",$verusuario="./usuarios.php?accion=ver");
echo <<<HTML
<body>
    <!--<div id="loading">Cargando...</div>-->
    
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="contenedor">
            <h2 class="text-center text-primary">Restablecer Contraseña</h2>
            <p class="text-center text-muted">
                Introduce tu correo electrónico para recibir un enlace de restablecimiento.
            </p>
            <form id="myForm" action="../vistas/usuarios.php?accion=correo_enviado" onsubmit="showLoading()" method="post">
                <div class="mb-3">
                    <input class="form-control" id="correo" placeholder="Correo electrónico" required type="email" name="correo">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-3 d-flex flex-column">
            <form id="myForm" action="../vistas/login.php?accion=login-html" onsubmit="showLoading()" method="post">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="">Regresar</i> 
                </button>
            </form>
            
            <form id="myForm" action="../index.php" onsubmit="showLoading()" method="post">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fa-solid fa-house"></i> Inicio
                </button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <!-- Custom JS -->
    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'flex';
        }
    </script>
</body>

</html>
HTML;
}
function restablecer_contraseña(){

    try {
        include_once "../conexion.php";
        $conexion = Conexion();
    
        if (!$conexion) {
            echo ('Error al conectar a la base de datos.');
        }
    
        if (!isset($_POST['correo'])) {
            echo ('Correo electrónico es requerido');
        }
        $correo = $_POST['correo'];
    
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $hora_expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        /*echo "<br><br> correo : ".$correo."</></>";
        echo "<br><br> token ; ".$token."</></>";
        echo "<br><br> hora expiracion: ".$hora_expiracion."</></>";die();*/
    
        $query = 'INSERT INTO password_reset (correo, token, expires_at) VALUES ($1, $2, $3)';
        $result = pg_query_params($conexion, $query, [$correo, $token, $hora_expiracion]);
    
        if (!$result) {
            $error = pg_last_error($conexion);
            echo ('Error al insertar en la base de datos: ' . $error);
        }
        if ($result) {
            
        $resetLink = "http://localhost/inventario-smart/vistas/usuario.php?accion=reset&token=$token";

        echo "este es un simulacro de el link que deberia mandar en caso de llegar al correo, pero como no llega ";

        echo "<a href='http://localhost/inventario-smart/vistas/usuarios.php?accion=contraseña&token=$token'>formulario para restablecer contraseña</a>";
        echo ' <br>  <a href="../vistas/pagina-principal/login.php">volver </a>';
    
        }
        $to = $correo;
        $subject = 'Restablecer Contraseña';
        $message = "Para restablecer tu contraseña, por favor haz clic en el siguiente enlace: $resetLink";
        $headers = 'From: no-reply@marrugobarrioscamilo2005@gmail.com' . "\r\n" .
                'Reply-To: no-reply@marrugobarrioscamilo2005@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
    
        $mail_sent = mail($to, $subject, $message, $headers);
    
        if ($mail_sent) {
            echo 'Hemos enviado un enlace para restablecer tu contraseña...';
            echo ' <br>  <a href="../vistas/pagina-principal/login.php">volver </a>';
        } else {
            throw new Exception('Hubo un problema al enviar el correo. Por favor, inténtelo de nuevo más tarde.');
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    
}

function Formulario_restablecer_contraseña(){

    include_once "../conexion.php";

    $conexion = conexion();


        $token = $_GET['token'];

    if (!isset($_GET['token'])) {
        die('Token es requerido');
    }

    // Usar parámetros de consulta para evitar inyecciones SQL
    $query = 'SELECT * FROM password_reset WHERE token = $1 AND expires_at > NOW()';
    $result = pg_query_params($conexion, $query, array($token));

    if (!$result) {
        die('Error en la consulta: ' . pg_last_error());
    }

    $reset = pg_fetch_assoc($result);

    if (!$reset) {
        die('El token es inválido o ha expirado.');
    }

    pg_close($conexion);

    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/restablecer_contraseña.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6ecbc133f.js" crossorigin="anonymous"></script>
    <style>
        
    </style>
</head>
<body>
    
    <div class="form-container">
        <h2>Restablecer Contraseña</h2>
        <form action="../vistas/usuarios.php?accion=restablecer" onsubmit="return validateForm()" method="post">
            <input type="hidden" name="token" value="$token">

            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                <div id="passwordError" class="error-message"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
        </form>
       
    </div>
    <div class="btn-back">
                <button class="btn btn-outline-secondary w-100">
                    <a href="usuarios.php?accion=ver">
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
    </div>
    

    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                passwordError.textContent = 'Las contraseñas no coinciden.';
                return false;
            }
            passwordError.textContent = '';
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;

}

function Restablecer(){
    
    include_once "../conexion.php";

if (isset($_POST['token'], $_POST['password'])) {
    $datos= [
        "token"=>$_POST["token"],
        "password"=>$_POST["password"],
        "ComfirmarContraseña"=>$_POST["confirm_password"]
    ];
    /*$token = $_POST['token'];
    $password = $_POST['password'];

    $ComfirmarContraseña = $_POST['confirm_password'];*/

    if ($datos["password"] !== $datos["ComfirmarContraseña"]) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    if (strlen($datos['password']) < 6) {
        echo "La contraseña debe tener al menos 6 caracteres.";
        exit;
    }

    $conexion = Conexion();

    echo $datos["token"];
    //echo $password;
    
    // Verificar el token
    $result = pg_query_params($conexion, "SELECT correo, expires_at FROM password_reset WHERE token = $1", array($datos["token"]));
    //echo $result;
    //var_dump($result);
    $reset = pg_fetch_assoc($result);

    if ($reset && $reset['expires_at'] > date('Y-m-d H:i:s')) {
        // Token válido, actualizar la contraseña
        $email = $reset['correo'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar la contraseña del usuario
        pg_query_params($conexion, "UPDATE usuarios SET contraseña = $1 WHERE correo = $2", array($hashedPassword, $email));

        // Eliminar el token usado
        pg_query_params($conexion, "DELETE FROM password_reset WHERE token = $1", array($datos["token"]));

        echo "La contraseña ha sido actualizada con éxito.";
        header("Location: ../vistas/login.php?accion=login-html");
        echo '<a href="../usuarios/usuarios.php">ver registros </a>';

    } else {
        echo "El enlace de restablecimiento no es inválido o ha expirado.";
        echo '<a href="../pagina-principal/login.php">volver</a>';

    }

    pg_close($conexion);
}

}


?>
