<?php

function Ingresar_categorias(){
        session_start();
    
        // Validar y sanitizar los datos recibidos del formulario
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        
        // Validar que todos los campos estén completos
        if (empty($nombre)) {
            echo "Todos los campos son obligatorios.";
            echo '<a href="categorias.php?accion=vercategorias">Volver</a>';
            exit;
        }
    
        // Conexión a la base de datos
        include_once "../conexion.php";
        $conexion = Conexion();
        // Fecha de ingreso
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d H:i:s');
    
        // Insertar los datos en la tabla equipos
        $consulta = "INSERT INTO categorias (nombre) 
                     VALUES ($1)";
        $result = pg_query_params($conexion, $consulta, array($nombre));
    
        if ($result) {
            echo "<script>alert('Equipo registrado correctamente.');</script>";
            header("Location: ./categorias.php?accion=vercategorias");
            exit;
        } else {
            echo "Error al registrar el equipo.";
            echo '<a href="formulario_registro_equipos.php">Volver</a>';
            exit;
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
   
    // echo "<br> nombre: ".$nombre."</br>";
   
    //Validar campos obligatorios
    if (empty($nombre)) {
         echo "Todos los campos son obligatorios.";
         exit;
     }
    
    // Incluir la conexión
    include_once "../conexion.php";
    $conexion = Conexion();

    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');

    // Consulta parametrizada segura
    $consulta = <<<SQL
        UPDATE categorias
        SET nombre = $1
        WHERE categoria_id = $2
SQL;

    // Ejecutar la consulta de manera segura
    $resultado_consulta = pg_query_params(
        $conexion,
        $consulta,
        [$nombre,$id]
    );

    if ($resultado_consulta) {
        // Redirigir al usuario después de una operación exitosa
        header("Location: categorias.php?accion=vercategorias");
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
        DELETE FROM categorias WHERE categoria_id = $1
SQL;
    $resultado = pg_query_params($conexion, $consulta,array($id));

    if ($resultado) {
        header("Location: categorias.php?accion=vercategorias");
        echo "el registro de id " . $id . " eliminado correctamente";
        exit;
    } else {
        echo "error ";
        exit;
    }
}

function Modificar_categorias()
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
    $consulta_usuario = "SELECT * FROM categorias WHERE categoria_id = $1";
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
        <title>Modificar Categoria</title>
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
            <h3 class="form-title">Modificar Categorias </h3>
            <form action="categorias.php?accion=actualizar&id=$id" method="post">
                <input type="hidden" name="id" value="{$id}">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control"  name="nombre" value="{$usuario->nombre}">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-pen"></i> Modificar
                </button>
            </form>
            
        </div>
    </body>
    <div class="btn-back">
                <button class="btn btn-outline-secondary w-100">
                    <a href="../categorias.php?accion=varcategorias">
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


?>