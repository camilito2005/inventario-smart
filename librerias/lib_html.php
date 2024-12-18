<?php


function ingresar_equipos()
{
    require_once "../conexion.php";

    $conexion = Conexion();

    
    $consulta = <<<SQL
    SELECT categoria_id, nombre FROM categorias
SQL;
$resultado_consulta = pg_query($conexion,$consulta);
$categorias= pg_fetch_all($resultado_consulta);

    session_start();

    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }

    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!--<link rel="stylesheet" href="../../css/cargando.css">
    <link rel="shortcut icon" href="../../fotos/agregar-usuario.png" type="image/x-icon">-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!--<script src="../js/cargando.js"></script>-->
    <title>Registro de equipos</title>
</head>
<body>
HTML;

    // if (isset($_SESSION["nombre"])) {
        echo <<<HTML
<div id="loading" style="display: none;">Cargando...</div>
<div class="container">
    <div class="row">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card">
                <div class="card-content">
                    <h4 class="center-align grey-text">Registro de equipos </h4>
                    <form id="myForm" onsubmit="showLoading()" action="../vistas/equipos.php?accion=registrar" method="post">
                        
                    
                        <div class="input-field">
                            <input id="nombre" type="text" name="nombre" required>
                            <label for="nombre">NOMBRE DEL RESPONSABLE</label>
                        </div>

                        <div class="input-field">
                            <input id="marca" type="text" name="marca" required>
                            <label for="mrca">Marca del equipo</label>
                        </div>
                        <div class="input-field">
                            <input id="modelo" type="text" name="modelo" required>
                            <label for="modelo">Modelo del equipo</label>
                        </div>

                        <div class="input-field">
                            <input id="ram" type="text" name="ram" required>
                            <label for="ram">Memoria ram del equipo</label>
                        </div>

                        <div class="input-field">
                            <input id="procesador" type="text" name="procesador" required>
                            <label for="procesador">Procesador del equipo</label>
                        </div>

                        <div class="col-md-6">
                <label for="">Filtrar por Categoría</label>
                <select id="category-filter" class="form-select" name="categoria">
HTML;
                    foreach ($categorias as $categoria) {
                        $id = $categoria["categoria_id"];
                        $descripcion = $categoria["nombre"];
                        echo "<option value=\"$id\">$descripcion</option>";
                    }
                    echo <<<HTML
                    </select>
                </div>
                        <div class="input-field">
                            <input id="almacenamiento" type="text" name="almacenamiento" required>
                            <label for="almacenamiento">Almacenamiento del equipo</label>
                        </div>

                        <div class="input-field">
                            <input id="dir-mac" type="text" name="dir-mac" required>
                            <label for="dir-mac">Direccon mac del equipo</label>
                        </div>

                        <div class="input-field">
                            <input id="perifericos" type="text" name="perifericos" required>
                            <label for="perifericos">Perifericos del equipo</label>
                        </div>
                        <div class="input-field">
                        <textarea id="observacion" type="text" name="observacion" >
                        </textarea>
                            <label for="observacion">Observacion</label>
                        </div>
                        <div class="input-field">
                            <input id="contraseña" type="text" name="contraseña" required>
                            <label for="contraseña">Contraseña del equipo</label>
                        </div>
                        <div class="center-align">
                            <button class="btn waves-effect waves-light" type="submit" name="registro">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-action center-align">
                    <form action="./equipos.php?accion=verequipos" onsubmit="showLoading()" method="post">
                        <button class="btn-flat waves-effect">
                            <i class="material-icons left">EQUIPOS</i> 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
//     } elseif ((!isset($_SESSION["cargo_id"]))) {
//         echo <<<HTML
// <div class="container">
//     <div class="row">
//         <div class="col s12">
//             <p class="center-align">Para continuar, inicia sesión.</p>
//             <div class="center-align">
//                 <a href="../pagina-principal/login.php?accion=login" class="btn waves-effect waves-light">
//                     Iniciar sesión
//                 </a>
//             </div>
//         </div>
//     </div>
// </div>
// HTML;
//     }

    echo <<<HTML
<div class="container center-align">
    <form action="../index.php" onsubmit="showLoading()" method="post">
        <button class="btn-flat waves-effect">
            <i class="material-icons left">Inicio</i> 
        </button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
</body>
</html>
HTML;
}

function Mostrarequipos(){
    session_start();

    require_once "../conexion.php";
    $conexion = Conexion();
    // Obtener filtro de categoría
    $filtro_categorias = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;

    $consulta_categorias = "SELECT categoria_id, nombre FROM categorias";
    $resultado_categorias = pg_query($conexion, $consulta_categorias);
    $categorias = pg_fetch_all($resultado_categorias);

    // Construir la consulta para filtrar equipos por categoría
    $consulta = "SELECT * FROM dispositivos";

   // Definir registros por página y calcular página actual
   $registros_por_pagina = 10;
   $pagina_actual = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
   $offset = ($pagina_actual - 1) * $registros_por_pagina;

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Tabla de equipos</title>
        <style>
            @media (max-width: 768px) {
                .table-responsive {
                    overflow-x: auto;
                }
            }
        </style>
    </head>
    <body>
HTML;

    if (isset($_SESSION["nombre"])) {
        $nombreUsuario = htmlspecialchars($_SESSION["nombre"] );

        echo <<<HTML
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inventario SmartInfo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span class="nav-link text-white">Hola, $nombreUsuario</span>
                        </li>
                        <li class="nav-item">
                            <form action="./usuarios.php?accion=cerrar" method="post" style="display: inline;">
                                <button class="btn btn-danger nav-link" type="submit">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
HTML;

        echo <<<HTML
        <a href="../vistas/equipos.php?accion=excel" class="btn btn-warning"><i class="fa-solid fa-file-excel">Excel</i></a>
        <a href="../vistas/equipos.php?accion=pdf" target="_blank" class="btn btn-success"><i class="fa-solid fa-file-pdf">Pdf</i></a>
        <div class="container mt-4">
            <h3 class="text-center text-secondary">Equipos</h3>
            <input type="hidden" id="role" value="{$_SESSION['descripcion']}">

            <!-- Barra de búsqueda y filtros -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="search" id="search" class="form-control" placeholder="Buscar...">
                        <!--<button class="btn btn-outline-secondary" type="button">Buscar</button>-->
                    </div>
                </div>
                <div class="col-md-6">
                <form method="get" action="equipos.php">
                    <input type="hidden" name="accion" value="verequipos">
                    <select id="category-filter" name="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
HTML;

    foreach ($categorias as $categoria) {
        $id = $categoria["categoria_id"];
        $descripcion = htmlspecialchars($categoria["nombre"]);
        $selected = ($id == $filtro_categorias) ? "selected" : "";
        echo "<option value=\"$id\" $selected>$descripcion</option>";
    }

    echo <<<HTML
                    </select>
                </form>
            </div>

            <!-- Tabla de equipos -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Memoria RAM</th>
                            <th>Procesador</th>
                            <th>Almacenamiento</th>
                            <th>Dirección MAC</th>
                            <th>Periféricos</th>
                            <th>Observacion</th>
                            <th>Categoria</th>
                            <th>Contraseña</th>
HTML;
if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
    echo <<< HTML
                            <th>Acciones</th>
HTML;
}
elseif (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "usuario") {
}
echo <<<HTML
                        </tr>
                    </thead>
                    <tbody id="resultados-equipos">
HTML;
$consulta = <<< SQL
SELECT  dispositivos.dispositivo_id AS id, 
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
    dispositivos.dispositivo_contraseña AS contraseña,
    dispositivos.categoria_id AS categorias,
    c.nombre AS categoria_descripcion
FROM dispositivos 
JOIN categorias c ON dispositivos.categoria_id = c.categoria_id
SQL;

//echo "<br><br>".$consulta;

if ($filtro_categorias) {
$consulta .= " WHERE dispositivos.categoria_id = $filtro_categorias";
}

$consulta .= " ORDER BY dispositivos.dispositivo_id LIMIT $registros_por_pagina OFFSET $offset";

//echo $consulta;
$query = pg_query($conexion, $consulta);
$equipos = pg_fetch_all($query);

        if ($equipos) {
            foreach ($equipos as $equipo) {
                $id_encriptado = base64_encode($equipo['dispositivo_id']);
                echo <<<HTML
                <tr>
                    <td>{$equipo['id']}</td>
                    <td>{$equipo['nombre']}</td>
                    <td>{$equipo['marca']}</td>
                    <td>{$equipo['modelo']}</td>
                    <td>{$equipo['ram']}</td>
                    <td>{$equipo['procesador']}</td>
                    <td>{$equipo['almacenamiento']}</td>
                    <td>{$equipo['dir_mac']}</td>
                    <td>{$equipo['perifericos']}</td>
                    <td>{$equipo['observacion']}</td>
                    <td>{$equipo['categoria_descripcion']}</td>
                    <td>{$equipo['contraseña']}</td>
HTML;
                    if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                        echo <<<HTML
                    <td>
                        <a href="equipos.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                            Modificar
                        </a>
                        <a href="equipos.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro?')" class="btn btn-sm btn-danger">
                            Eliminar
                        </a>
                    </td>
                </tr>
HTML;
                    }
            }
        } else {
            echo <<<HTML
            <tr>
                <td colspan="12" class="text-center">No hay equipos registrados.</td>
            </tr>
HTML;
        }

        echo <<<HTML
                    </tbody>
                </table>
            </div>
HTML;

        // Contar total de registros
        $consulta_total = "SELECT COUNT(*) AS total FROM dispositivos";
        if ($filtro_categorias) {
            $consulta_total .= " WHERE categoria_id = $filtro_categorias";
        }
        $resultado_total = pg_query($conexion, $consulta_total);
        $total_registros = pg_fetch_result($resultado_total, 0, 'total');
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Paginación
        echo '<nav class="d-flex justify-content-center">';
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = $i == $pagina_actual ? 'active' : '';
            echo <<<HTML
            <li class="page-item $active">
                <a class="page-link" href="equipos.php?accion=verequipos&page=$i&categoria=$filtro_categorias">$i</a>
            </li>
HTML;
        }
        echo '</ul>';
        echo '</nav>';
    }
    echo <<<HTML
        <div class="text-center my-4">
                <a href="equipos.php?accion=aggequipos" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i> Agregar Equipos
                </a>
            </div>

            <!-- Botón para volver al inicio -->
            <div class="text-center">
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-house"></i> Volver al inicio
                </a>
            </div>

        <script src="../js/buscar.js">
        </script>
HTML;

    echo <<<HTML
      <!-- Botón para agregar usuarios -->
      
    </body>
    </html>
HTML;
}


function Mostrarequipos02()
{
    session_start();
    require_once "../conexion.php";

    $conexion = Conexion();


    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }
    
     // Obtener filtro de categoría
     $filtro_categorias = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;

     $consulta_categorias = "SELECT categoria_id, nombre FROM categorias";
     $resultado_categorias = pg_query($conexion, $consulta_categorias);
     $categorias = pg_fetch_all($resultado_categorias);
 
     // Construir la consulta para filtrar equipos por categoría
     $consulta = "SELECT * FROM dispositivos";

    // Definir registros por página y calcular página actual
    $registros_por_pagina = 10;
    $pagina_actual = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($pagina_actual - 1) * $registros_por_pagina;
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Tabla de equipos</title>
        <style>
            @media (max-width: 768px) {
                .table-responsive {
                    overflow-x: auto;
                }
            }
        </style>
    </head>
    <body>
HTML;

    if (isset($_SESSION["nombre"])) {
        $nombreUsuario = htmlspecialchars($_SESSION["nombre"]);
        echo <<<HTML
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <input type="hidden" id="role" value="{$_SESSION['descripcion']}">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inventario SmartInfo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span class="nav-link text-white">Hola, $nombreUsuario</span>
                        </li>
                        <li class="nav-item">
                            <form action="./usuarios.php?accion=cerrar" method="post" style="display: inline;">
                                <button class="btn btn-danger nav-link" type="submit">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
HTML;

        echo <<<HTML
        <div class="container mt-4">
            <h3 class="text-center text-secondary">Equipos</h3>
            <!-- Barra de búsqueda y filtros -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="search" id="search" class="form-control" placeholder="Buscar...">
                    </div>
                </div>
                <div class="col-md-6">
                <form method="get" action="equipos.php">
                    <input type="hidden" name="accion" value="verequipos">
                    <select id="category-filter" name="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
HTML;

    foreach ($categorias as $categoria) {
        $id = $categoria["categoria_id"];
        $descripcion = htmlspecialchars($categoria["nombre"]);
        $selected = ($id == $filtro_categorias) ? "selected" : "";
        echo "<option value=\"$id\" $selected>$descripcion</option>";
    }

    echo <<<HTML
                    </select>
                </form>
            </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Memoria RAM</th>
                            <th>Procesador</th>
                            <th>Almacenamiento</th>
                            <th>Dirección MAC</th>
                            <th>Periféricos</th>
                            <th>Observación</th>
                            <th>Categoria</th>
                            <th>Contraseña</th>
HTML;
            if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                        echo <<<HTML
                           <th>Acciones</th>
HTML;
                            }
                            echo <<<HTML
                        </tr>
                    </thead>
                    <tbody id="tabla-equipos">
HTML;
// Consulta con paginación
$consulta = <<< SQL
SELECT  dispositivos.dispositivo_id AS id, 
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
    dispositivos.dispositivo_contraseña AS contraseña,
    dispositivos.categoria_id AS categorias,
    c.nombre AS categoria_descripcion
FROM dispositivos 
JOIN categorias c ON dispositivos.categoria_id = c.categoria_id
SQL;

//echo "<br><br>".$consulta;

if ($filtro_categorias) {
$consulta .= " WHERE dispositivos.categoria_id = $filtro_categorias";
}

$consulta .= " ORDER BY dispositivos.dispositivo_id LIMIT $registros_por_pagina OFFSET $offset";

//echo $consulta;
$query = pg_query($conexion, $consulta);
$equipos = pg_fetch_all($query);

        if ($equipos) {
            foreach ($equipos as $equipo) {
                $id_encriptado = base64_encode($equipo['dispositivo_id']);
                echo <<<HTML
                <tr>
                    <td>{$equipo['id']}</td>
                    <td>{$equipo['nombre']}</td>
                    <td>{$equipo['marca']}</td>
                    <td>{$equipo['modelo']}</td>
                    <td>{$equipo['ram']}</td>
                    <td>{$equipo['procesador']}</td>
                    <td>{$equipo['almacenamiento']}</td>
                    <td>{$equipo['dir_mac']}</td>
                    <td>{$equipo['perifericos']}</td>
                    <td>{$equipo['observacion']}</td>
                    <td>{$equipo['categoria_descripcion']}</td>
                    <td>{$equipo['contraseña']}</td>
HTML;
                    if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                        echo <<<HTML
                    <td>
                        <a href="equipos.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                            Modificar
                        </a>
                        <a href="equipos.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro?')" class="btn btn-sm btn-danger">
                            Eliminar
                        </a>
                    </td>
                </tr>
HTML;
                    }
            }
        } else {
            echo <<<HTML
            <tr>
                <td colspan="12" class="text-center">No hay equipos registrados.</td>
            </tr>
HTML;
        }

        echo <<<HTML
                    </tbody>
                </table>
            </div>
HTML;

        // Contar total de registros
        $consulta_total = "SELECT COUNT(*) AS total FROM dispositivos";
        if ($filtro_categorias) {
            $consulta_total .= " WHERE categoria_id = $filtro_categorias";
        }
        $resultado_total = pg_query($conexion, $consulta_total);
        $total_registros = pg_fetch_result($resultado_total, 0, 'total');
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Paginación
        echo '<nav class="d-flex justify-content-center">';
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = $i == $pagina_actual ? 'active' : '';
            echo <<<HTML
            <li class="page-item $active">
                <a class="page-link" href="equipos.php?accion=verequipos&page=$i&categoria=$filtro_categorias">$i</a>
            </li>
HTML;
        }
        echo '</ul>';
        echo '</nav>';
    }

    echo <<<HTML

        <div class="text-center my-4">
                <a href="equipos.php?accion=aggequipos" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i> Agregar Equipos
                </a>
            </div>

            <!-- Botón para volver al inicio -->
            <div class="text-center">
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-house"></i> Volver al inicio
                </a>
            </div>
            <script src="../js/buscar.js"></script>
    </body>
    </html>
HTML;
}








function Mostrarequipos000()
{
    session_start();
    require_once "../conexion.php";

    $conexion = Conexion();


    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }
    
     // Obtener filtro de categoría
     $filtro_categorias = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;

     $consulta_categorias = "SELECT categoria_id, nombre FROM categorias";
     $resultado_categorias = pg_query($conexion, $consulta_categorias);
     $categorias = pg_fetch_all($resultado_categorias);
 
     // Construir la consulta para filtrar equipos por categoría
     $consulta = "SELECT * FROM dispositivos";

    // Definir registros por página y calcular página actual
    $registros_por_pagina = 10;
    $pagina_actual = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Tabla de equipos</title>
        <style>
            @media (max-width: 768px) {
                .table-responsive {
                    overflow-x: auto;
                }
            }
        </style>
    </head>
    <body>
HTML;

    if (isset($_SESSION["nombre"])) {
        $nombreUsuario = htmlspecialchars($_SESSION["nombre"]);
        echo <<<HTML
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <input type="hidden" id="role" value="{$_SESSION['descripcion']}">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inventario SmartInfo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span class="nav-link text-white">Hola, $nombreUsuario</span>
                        </li>
                        <li class="nav-item">
                            <form action="./usuarios.php?accion=cerrar" method="post" style="display: inline;">
                                <button class="btn btn-danger nav-link" type="submit">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
HTML;

        echo <<<HTML
        <div class="container mt-4">
            <h3 class="text-center text-secondary">Equipos</h3>
            <!-- Barra de búsqueda y filtros -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="search" id="search" class="form-control" placeholder="Buscar...">
                        <!--<button class="btn btn-outline-secondary" type="button">Buscar</button>-->
                    </div>
                </div>
                <div class="col-md-6">
                <form method="get" action="equipos.php">
                    <input type="hidden" name="accion" value="verequipos">
                    <select id="category-filter" name="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
HTML;

    foreach ($categorias as $categoria) {
        $id = $categoria["categoria_id"];
        $descripcion = htmlspecialchars($categoria["nombre"]);
        $selected = ($id == $filtro_categorias) ? "selected" : "";
        echo "<option value=\"$id\" $selected>$descripcion</option>";
    }

    echo <<<HTML
                    </select>
                </form>
            </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Memoria RAM</th>
                            <th>Procesador</th>
                            <th>Almacenamiento</th>
                            <th>Dirección MAC</th>
                            <th>Periféricos</th>
                            <th>Categoria</th>
                            <th>Observación</th>
                            <th>Contraseña</th>
HTML;
            if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                        echo <<<HTML
                           <th>Acciones</th>
HTML;
                            }
                            echo <<<HTML
                        </tr>
                    </thead>
                    <tbody id="tabla-equipos">
HTML;

        // Consulta con paginación
        $consulta = "SELECT * 
             FROM dispositivos d
             JOIN categorias c ON d.categoria_id = c.categoria_id";

if ($filtro_categorias) {
    $consulta .= " WHERE d.categoria_id = $filtro_categorias";
}

$consulta .= " ORDER BY d.dispositivo_id LIMIT $registros_por_pagina OFFSET $offset";

       echo $consulta;
        $query = pg_query($conexion, $consulta);
        $equipos = pg_fetch_all($query);

        if ($equipos) {
            foreach ($equipos as $equipo) {
                $id_encriptado = base64_encode($equipo['dispositivo_id']);
                echo <<<HTML
                <tr>
                    <td>{$equipo['dispositivo_id']}</td>
                    <td>{$equipo['dispositivo_nombre_usuario']}</td>
                    <td>{$equipo['dispositivo_marca']}</td>
                    <td>{$equipo['dispositivo_modelo']}</td>
                    <td>{$equipo['dispositivo_ram']}</td>
                    <td>{$equipo['dispositivo_procesador']}</td>
                    <td>{$equipo['dispositivo_almacenamiento']}</td>
                    <td>{$equipo['dispositivo_direccion_mac']}</td>
                    <td>{$equipo['dispositivo_perifericos']}</td>
                    <td>{$equipo['nombre']}</td>
                    <td>{$equipo['observacion']}</td>
                    <td>{$equipo['dispositivo_contraseña']}</td>
HTML;
                    if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                        echo <<<HTML
                    <td>
                        <a href="equipos.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                            Modificar
                        </a>
                        <a href="equipos.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro?')" class="btn btn-sm btn-danger">
                            Eliminar
                        </a>
                    </td>
                </tr>
HTML;
                    }
            }
        } else {
            echo <<<HTML
            <tr>
                <td colspan="12" class="text-center">No hay equipos registrados.</td>
            </tr>
HTML;
        }

        echo <<<HTML
                    </tbody>
                </table>
            </div>
HTML;

        // Contar total de registros
        $consulta_total = "SELECT COUNT(*) AS total FROM dispositivos";
        $resultado_total = pg_query($conexion, $consulta_total);
        $total_registros = pg_fetch_result($resultado_total, 0, 'total');
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Paginación
        echo '<nav class="d-flex justify-content-center">';
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = $i == $pagina_actual ? 'active' : '';
            echo <<<HTML
            <li class="page-item $active">
                <a class="page-link" href="equipos.php?accion=verequipos&page=$i">$i</a>
            </li>
HTML;
        }
        echo '</ul>';
        echo '</nav>';
    } else {
    }

    echo <<<HTML
    <script>
        $(document).ready(function () {
            $('#buscador').on('keyup', function () {
                var valor = $(this).val().toLowerCase();
                $('#tabla-equipos tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
                });
            });
        });
    </script>
    
        <div class="text-center my-4">
                <a href="equipos.php?accion=aggequipos" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i> Agregar Equipos
                </a>
            </div>

            <!-- Botón para volver al inicio -->
            <div class="text-center">
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-house"></i> Volver al inicio
                </a>
            </div>
            <script src="../js/buscar.js"></script>
    </body>
    </html>
HTML;
}

//
                                            //${encodeURIComponent(idencriptado)}  ${encodeURIComponent(idencriptado)}
function Principal() {
    session_start();
    
    echo <<<HTML
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario SmartInfo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inventario SmartInfo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
HTML;

    if (isset($_SESSION['nombre'])) {
        // Usuario autenticado: mostrar nombre/correo y botón de cerrar sesión
        $nombreUsuario = htmlspecialchars($_SESSION['nombre']);
        echo <<<HTML
                    <li class="nav-item">
                        <span class="nav-link text-white">Hola, $nombreUsuario</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./vistas/usuarios.php?accion=perfil">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <form action="./vistas/usuarios.php?accion=cerrar" method="post" style="display: inline;">
                            <button class="btn btn-danger nav-link" type="submit">Cerrar Sesión</button>
                        </form>
                    </li>
HTML;
    } else {
        // Usuario no autenticado: mostrar opción de login
        echo <<<HTML
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/login.php?accion=login-html">Login</a>
                    </li>
HTML;
    }

    echo <<<HTML
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/equipos.php?accion=aggequipos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/categorias.php?accion=vercategorias">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Movimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/estadisticas.php?accion=masmarcas">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/usuarios.php?accion=aggusuarios">Usuarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Bienvenido al Inventario SmartInfo</h1>
            <p class="lead">Gestiona tus productos de manera fácil y eficiente.</p>
            <a href="./vistas/equipos.php?accion=verequipos" class="btn btn-primary btn-lg">Explorar Inventario</a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            <!-- Productos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text">Añade, edita o elimina productos de tu inventario.</p>
                        <a href="./vistas/equipos.php?accion=verequipos" class="btn btn-primary">Gestionar</a>
                    </div>
                </div>
            </div>
            <!-- Categorías -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categorías</h5>
                        <p class="card-text">Organiza tus productos por categorías y subcategorías.</p>
                        <a href="#" class="btn btn-primary">Explorar</a>
                    </div>
                </div>
            </div>
            <!-- Movimientos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Movimientos</h5>
                        <p class="card-text">Registra entradas y salidas de productos.</p>
                        <a href="#" class="btn btn-primary">Registrar</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Reportes -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Genera reportes detallados sobre tu inventario.</p>
                        <a href="#" class="btn btn-primary">Ver Reportes</a>
                    </div>
                </div>
            </div>
            <!-- Usuarios -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Gestiona roles y permisos para el sistema.</p>
                        <a href="./vistas/usuarios.php?accion=ver" class="btn btn-primary">Administrar</a>
                    </div>
                </div>
            </div>
            <!-- Alertas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Alertas</h5>
                        <p class="card-text">Revisa productos con stock bajo.</p>
                        <a href="#" class="btn btn-primary">Ver Alertas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Buscador -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Buscador</h5>
                        <p class="card-text">Encuentra productos rápidamente utilizando filtros avanzados.</p>
                        <a href="#" class="btn btn-primary">Buscar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white text-center py-3">
        <p class="mb-0">&copy; 2024 Inventario SmartInfo. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}



function Principal000($usuarioLogueado = null) {
    // Si hay un usuario logueado, tomamos su nombre

    if (isset($_SESSION["nombre"])) {
        $nombreUsuario = $_SESSION["nombre"];
    }
    elseif (!isset($_SESSION["nombre"])) {
        $nombreUsuario = NULL;
    }
    //$nombreUsuario = $usuarioLogueado ? htmlspecialchars($usuarioLogueado['nombre']) : null;

    echo <<<HTML
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario SmartInfo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inventario SmartInfo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/equipos.php?accion=aggequipos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Movimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/usuarios.php?accion=ver">Usuarios</a>
                    </li>
HTML;

    // Si hay un usuario logueado
    if (isset($_SESSION["correo"])) {
        echo <<<HTML
                    <li class="nav-item">
                        <a class="nav-link" href="#">Bienvenido, $nombreUsuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="./vistas/login.php?accion=cerrar-sesion">Cerrar sesión</a>
                    </li>
HTML;
    } else {
        // Mostrar opción de Login si no hay sesión iniciada
        echo <<<HTML
                    <li class="nav-item">
                        <a class="nav-link" href="./vistas/login.php?accion=login-html">Login</a>
                    </li>
HTML;
    }

    echo <<<HTML
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Bienvenido al Inventario SmartInfo</h1>
            <p class="lead">Gestiona tus productos de manera fácil y eficiente.</p>
            <a href="#" class="btn btn-primary btn-lg">Explorar Inventario</a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            <!-- Productos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text">Añade, edita o elimina productos de tu inventario.</p>
                        <a href="#" class="btn btn-primary">Gestionar</a>
                    </div>
                </div>
            </div>
            <!-- Categorías -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categorías</h5>
                        <p class="card-text">Organiza tus productos por categorías y subcategorías.</p>
                        <a href="#" class="btn btn-primary">Explorar</a>
                    </div>
                </div>
            </div>
            <!-- Movimientos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Movimientos</h5>
                        <p class="card-text">Registra entradas y salidas de productos.</p>
                        <a href="#" class="btn btn-primary">Registrar</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Reportes -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Genera reportes detallados sobre tu inventario.</p>
                        <a href="#" class="btn btn-primary">Ver Reportes</a>
                    </div>
                </div>
            </div>
            <!-- Usuarios -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Gestiona roles y permisos para el sistema.</p>
                        <a href="#" class="btn btn-primary">Administrar</a>
                    </div>
                </div>
            </div>
            <!-- Alertas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Alertas</h5>
                        <p class="card-text">Revisa productos con stock bajo.</p>
                        <a href="#" class="btn btn-primary">Ver Alertas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Buscador -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Buscador</h5>
                        <p class="card-text">Encuentra productos rápidamente utilizando filtros avanzados.</p>
                        <a href="#" class="btn btn-primary">Buscar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white text-center py-3">
        <p class="mb-0">&copy; 2024 Inventario SmartInfo. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}



function Formulario_usuarios(){
    session_start();

    /*if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }*/

    $usuario_actual = $_SESSION["nombre"];
    //echo "rol : ".$_SESSION['descripcion'];
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!--<link rel="stylesheet" href="../../css/cargando.css">
    <link rel="shortcut icon" href="../../fotos/agregar-usuario.png" type="image/x-icon">-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!--<script src="../js/cargando.js"></script>-->
    <title>Registro de Usuarios</title>
</head>
<body>
HTML;

    if (isset($_SESSION["nombre"])) {
        echo <<<HTML
        <div>
        <span class="me-3">Usuario: <strong>$usuario_actual
                                    </strong>
        </span>
        <a href="../vistas/usuarios.php?accion=cerrar" class="btn btn-danger btn-sm">Cerrar sesión</a>
    </div>
HTML;
    }
        echo <<<HTML
<div id="loading" style="display: none;">Cargando...

</div>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
                        
                    </div>                                          
    <div class="row">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            
            <div class="card">
                <div class="card-content">
                    <h4 class="center-align grey-text">Registro de Usuarios</h4>
                    <form id="myForm" onsubmit="showLoading()" action="usuarios.php?accion=registrar" method="post">
                        
                        <div class="input-field">
                            <input id="dni" type="text" name="dni" required>
                            <label for="dni">DNI</label>
                        </div>
HTML;

        if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
            echo <<<HTML
                        <div class="input-field">
                            <select name="rol" required>
                                <option value="1">Administrador</option>
                                <option value="2">Usuario</option>
                            </select>
                            <label for="rol">Rol</label>
                        </div>
HTML;
        } else {
            echo <<<HTML
                        <div class="input-field">
                            <select name="rol" required>
                                <option value="2">Usuario</option>
                            </select>
                            <label for="rol">Rol</label>
                        </div>
HTML;
        }

        echo <<<HTML
                        <div class="input-field">
                            <input id="nombre" type="text" name="nombre" required>
                            <label for="nombre">Nombre</label>
                        </div>

                        <div class="input-field">
                            <input id="apellido" type="text" name="apellido" required>
                            <label for="apellido">Apellidos</label>
                        </div>

                        <div class="input-field">
                            <input id="telefono" type="tel" name="telefono" required>
                            <label for="telefono">Número Telefónico</label>
                        </div>

                        <div class="input-field">
                            <input id="direccion" type="text" name="direccion" required>
                            <label for="direccion">Dirección</label>
                        </div>

                        <div class="input-field">
                            <input id="correo" type="email" name="correo" required>
                            <label for="correo">Correo Electrónico</label>
                        </div>

                        <div class="input-field">
                            <input id="contraseña" type="password" name="contraseña" required>
                            <label for="contraseña">Contraseña</label>
                        </div>

                        <div class="input-field">
                            <input id="confirmar_contraseña" type="password" name="confirmar_contraseña" required>
                            <label for="confirmar_contraseña">Confirmar Contraseña</label>
                        </div>

                        <div class="center-align">
                            <button class="btn waves-effect waves-light" type="submit" name="registro">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-action center-align">
                    <form action="./usuarios.php?accion=ver" onsubmit="showLoading()" method="post">
                        <button class="btn-flat waves-effect">
                            <i class="material-icons left">Usuarios</i> 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
     /*} elseif ((!isset($_SESSION["cargo_id"]))) {
         echo <<<HTML
 <div class="container">
     <div class="row">
         <div class="col s12">
             <p class="center-align">Para continuar, inicia sesión.</p>
             <div class="center-align">
                 <a href="../pagina-principal/login.php?accion=login" class="btn waves-effect waves-light">
                     Iniciar sesión
                 </a>
             </div>
         </div>
     </div>
 </div>
HTML;
     }*/

    echo <<<HTML
<div class="container center-align">
    <form action="../index.php" onsubmit="showLoading()" method="post">
        <button class="btn-flat waves-effect">
            <i class="material-icons left">Inicio</i> 
        </button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
</body>
</html>
HTML;
}


function Mostrar_usuarios000()
{
    session_start();
    include_once "../conexion.php";

    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }
    
    $usuario_actual = $_SESSION["nombre"];
    $conexion = Conexion();

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <title>Gestión de Usuarios</title>
    </head>
    <body>
        <div class="container mt-4">
            <!-- Header con usuario en sesión y botón de cerrar sesión -->
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-secondary">Gestión de Usuarios</h3>
                <div>
                    <span class="me-3">Usuario: <strong>$usuario_actual</strong></span>
                    <a href="../vistas/usuarios.php?accion=cerrar" class="btn btn-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>

            <!-- Menú de categorías -->
            <div class="my-4">
                <h5 class="text-secondary">Filtrar por cargo:</h5>
                <form method="get" action="usuarios.php">
                    <select name="cargo" class="form-select w-50 d-inline" onchange="this.form.submit()">
                        <option value="">Todos los cargos</option>
HTML;

    // Obtener lista de roles/cargos desde la base de datos
    $consulta_roles = "SELECT id, descripcion FROM roles";
    $roles = pg_fetch_all(pg_query($conexion, $consulta_roles));
    if ($roles) {
        foreach ($roles as $rol) {
            echo "<option value='{$rol['id']}'>{$rol['descripcion']}</option>";
        }
    }

    echo <<<HTML
                    </select>
                </form>
            </div>

            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;

    // Filtrar por cargo si aplica
    $filtro_cargo = isset($_GET['cargo']) ? intval($_GET['cargo']) : null;
    $consulta_usuarios = "SELECT u.id, u.dni, u.nombre, u.apellido, u.telefono, u.direccion, u.correo, r.descripcion AS rol 
                          FROM usuarios u 
                          INNER JOIN roles r ON u.rol_id = r.id";
    if ($filtro_cargo) {
        $consulta_usuarios .= " WHERE u.rol_id = $filtro_cargo";
    }

    $query_usuarios = pg_query($conexion, $consulta_usuarios);
    $usuarios = pg_fetch_all($query_usuarios);

    if ($usuarios) {
        foreach ($usuarios as $usuario) {
            $id_encriptado = base64_encode($usuario['id']);
            echo <<<HTML
                        <tr>
                            <td>{$usuario['id']}</td>
                            <td>{$usuario['dni']}</td>
                            <td>{$usuario['nombre']}</td>
                            <td>{$usuario['apellido']}</td>
                            <td>{$usuario['telefono']}</td>
                            <td>{$usuario['direccion']}</td>
                            <td>{$usuario['correo']}</td>
                            <td>{$usuario['rol']}</td>
                            <td>
                                <a href="usuarios.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i> Modificar
                                </a>
                                <a href="usuarios.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro de eliminar este usuario?')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
HTML;
        }
    } else {
        echo <<<HTML
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron usuarios.</td>
                        </tr>
HTML;
    }

    echo <<<HTML
                    </tbody>
                </table>
            </div>

            <!-- Botón para agregar usuarios -->
            <div class="text-center my-4">
                <a href="usuarios.php?accion=aggusuarios" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </a>
            </div>

            <!-- Botón para volver al inicio -->
            <div class="text-center">
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-house"></i> Volver al inicio
                </a>
            </div>
        </div>

        <!-- Scripts -->
        <script src="../js/pregunta.js"></script>
    </body>
    </html>
HTML;
}


function Mostrar_usuarios()
{
    session_start();
    include_once "../conexion.php";

    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html");
        exit();
    }

    $usuario_actual = $_SESSION["nombre"];
    $conexion = Conexion();

    // Definición de variables para la paginación
    $usuarios_por_pagina = 10; // Número de usuarios a mostrar por página
    $pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $offset = ($pagina_actual - 1) * $usuarios_por_pagina;

    // Filtrar por cargo si aplica
    $filtro_cargo = isset($_GET['cargo']) ? intval($_GET['cargo']) : null;

    // Consulta base con paginación
    $consulta_usuarios = "SELECT u.id, u.dni, u.nombre, u.apellido, u.telefono, u.direccion, u.correo, r.descripcion AS rol 
                          FROM usuarios u 
                          INNER JOIN roles r ON u.rol_id = r.id";

    // Si hay un filtro, se añade a la consulta
    if ($filtro_cargo) {
        $consulta_usuarios .= " WHERE u.rol_id = $filtro_cargo";
    }

    // Añadir paginación
    $consulta_usuarios .= " LIMIT $usuarios_por_pagina OFFSET $offset";
    $query_usuarios = pg_query($conexion, $consulta_usuarios);
    $usuarios = pg_fetch_all($query_usuarios);

    // Obtener total de registros para calcular páginas
    $consulta_total = "SELECT COUNT(*) AS total FROM usuarios";
    if ($filtro_cargo) {
        $consulta_total .= " WHERE rol_id = $filtro_cargo";
    }
    $query_total = pg_query($conexion, $consulta_total);
    $total_usuarios = intval(pg_fetch_result($query_total, 0, 'total'));
    $total_paginas = ceil($total_usuarios / $usuarios_por_pagina);

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Gestión de Usuarios</title>
    </head>
    <body>
        <div class="container mt-4">
            <!-- Header con usuario en sesión y botón de cerrar sesión -->
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-secondary">Gestión de Usuarios</h3>
                <div>
                    <span class="me-3">Usuario: <strong>$usuario_actual</strong></span>
                    <a href="../vistas/usuarios.php?accion=cerrar" class="btn btn-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
            <input type="hidden" id="role" value="{$_SESSION['descripcion']}">

            <!-- Menú de categorías -->
            <div class="my-4">
                <h5 class="text-secondary">Filtrar por cargo:</h5>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="search" id="search" class="form-control" placeholder="Buscar...">
                        <!--<button class="btn btn-outline-secondary" type="button">Buscar</button>-->
                    </div>
                </div>
                <form method="get" action="usuarios.php?accion=ver&">
                <input type="hidden" name="accion" value="ver">
                    <select name="cargo" class="form-select w-50 d-inline" onchange="this.form.submit()">
                        <option value="">Todos los cargos</option>
HTML;

    // Obtener lista de roles/cargos desde la base de datos
    $consulta_roles = "SELECT id, descripcion FROM roles";
    $roles = pg_fetch_all(pg_query($conexion, $consulta_roles));
    if ($roles) {
        foreach ($roles as $rol) {
            $selected = ($filtro_cargo == $rol['id']) ? "selected" : "";
            echo "<option value='{$rol['id']}' $selected>{$rol['descripcion']}</option>";
        }
    }

    echo <<<HTML
                    </select>
                </form>
            </div>

            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Correo</th>
HTML;
if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
    echo <<<HTML
                            <th>Cargo</th>
                            <th>Acciones</th>
HTML;
}
elseif (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "usuario") {

}
echo <<<HTML
                        </tr>
                    </thead>
                    <tbody id="resultados-usuarios">
HTML;

    if ($usuarios) {
        foreach ($usuarios as $usuario) {
            $id_encriptado = base64_encode($usuario['id']);
            echo <<<HTML
                        <tr>
                            <td>{$usuario['id']}</td>
                            <td>{$usuario['dni']}</td>
                            <td>{$usuario['nombre']}</td>
                            <td>{$usuario['apellido']}</td>
                            <td>{$usuario['telefono']}</td>
                            <td>{$usuario['direccion']}</td>
                            <td>{$usuario['correo']}</td>
HTML;
if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
echo <<<HTML
                            <td>{$usuario['rol']}</td>
                            <td>
                                <a href="usuarios.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i> Modificar
                                </a>
                                <a href="usuarios.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro de eliminar este usuario?')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>

HTML;
}
elseif (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "usuarios") {
    
}
        }
    } else {
        echo <<<HTML
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron usuarios.</td>
                        </tr>
HTML;
    }

    echo <<<HTML
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
HTML;

    // Generar botones de paginación
    for ($i = 1; $i <= $total_paginas; $i++) {
        $active = ($i == $pagina_actual) ? "active" : "";
        echo <<<HTML
                    <li class="page-item $active">
                        <a class="page-link" href="usuarios.php?accion=ver&pagina=$i&cargo=$filtro_cargo">$i </a>
                    </li>
HTML;
    }

    echo <<<HTML
                </ul>
            </nav>

            <!-- Botón para agregar usuarios -->
            <div class="text-center my-4">
                <a href="usuarios.php?accion=aggusuarios" class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </a>
            </div>

            <!-- Botón para volver al inicio -->
            <div class="text-center">
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-house"></i> Volver al inicio
                </a>
            </div>
        </div>
        <script src="../js/busqueda.js"></script>
    </body>
    </html>
HTML;
}


function Login_html() {

    $mensaje = $_REQUEST["mensaje"];
    if (empty($mensaje)) {
        $mensaje = "";
    }
    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6ecbc133f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <!--<link rel="stylesheet" href="../../css/cargando.css">-->
    <link rel="stylesheet" href="../css/login.css">
    <!--<script src="../js/cargando.js"></script>-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión</title>
</head>

<body>
    <div id="loading">Cargando...</div>
    <p>{$mensaje}</p>
    <div class="mx-auto contenedor">
        <div class="formulario_registro">
            <form id="myForm" class="mx-auto" action="./login.php?accion=login" onsubmit="showLoading()" method="post">
                <h2 class="text-center text-secondary">Bienvenido</h2>
                <p class="text-center text-secondary">Inicia sesión</p>
                <input class="form-control" placeholder="Correo" required type="text" name="correo">
                <br>
                <input class="form-control" placeholder="Contraseña" required type="password" name="contraseña">
                <br>
                <input class="btn btn-primary" name="inicio" type="submit" value="Entrar">
                <br>
                <a class="link-recuperar" href="../usuarios/usuarios.php?accion=recuperar">¿Olvidaste tu contraseña?</a>
            </form>
        </div>
    </div>
    <form id="myForm" action="../vistas/usuarios.php?accion=aggusuarios" onsubmit="showLoading()" method="post">
        <button class="btn btn-outline-secondary" value="inicio">
            <i class="fa-solid fa-user-plus"></i> Agregar usuarios
        </button>
    </form>
    <form id="myForm" action="../index.php" onsubmit="showLoading()" method="post">
        <button class="btn btn-outline-secondary" value="inicio">
            <i class="fa-solid fa-house"></i> Inicio
        </button>
    </form>
</body>

</html>
HTML;
    echo $html;
}

function MostrarCategorias(){
    session_start();

    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }

    //echo "cargo: ".$_SESSION["descripcion"];
    //echo "cargo : ".$_SESSION["rol_id"];
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <title>Tabla de Categorias</title>
            <style>
                @media (max-width: 768px) {
                    .table-responsive {
                        overflow-x: auto;
                    }
                }
            </style>
        </head>
        <body>
HTML;
    
        if (isset($_SESSION["nombre"])) {
            $nombreUsuario = htmlspecialchars($_SESSION["nombre"] );
    
            echo <<<HTML
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="../index.php">Inventario SmartInfo</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <span class="nav-link text-white">Hola, $nombreUsuario</span>
                            </li>
                            <li class="nav-item">
                                <form action="./usuarios.php?accion=cerrar" method="post" style="display: inline;">
                                    <button class="btn btn-danger nav-link" type="submit">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
HTML;
    
            echo <<<HTML
            <div class="container mt-4">
                <h3 class="text-center text-secondary">Categorias</h3>
    
                <!-- Barra de búsqueda y filtros -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="search" id="search" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
    
                <!-- Tabla de equipos -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Categoria</th>
HTML;
if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
    echo <<<HTML
                                <th>Acciones</th>
HTML;
}
elseif (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "usuario") {
    
}
echo <<<HTML
                            </tr>
                        </thead>
                        <tbody id="resultados-equipos">
HTML;
    
            include_once "../conexion.php";
            $conexion = Conexion();
            $consulta = "SELECT * FROM categorias ORDER BY categoria_id"; // Paginar con LIMIT
            $query = pg_query($conexion, $consulta);
            $equipos = pg_fetch_all($query);
    
            if ($equipos) {
                foreach ($equipos as $equipo) {
                    $id_encriptado = base64_encode($equipo['categoria_id']);
    
                    echo <<<HTML
                    <tr>
                        <td>{$equipo['categoria_id']}</td>
                        <td>{$equipo['nombre']}</td>
HTML;
if (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "administrador") {
                    echo <<<HTML
                        <td>
                            <a href="categorias.php?accion=modificar-html&id={$id_encriptado}" class="btn btn-sm btn-primary">
                                <i class="fas fa-pen"></i> Modificar
                            </a>
                            <a href="categorias.php?accion=eliminar&id={$id_encriptado}" onclick="return confirm('¿Estás seguro de que deseas eliminar este equipo?')" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
HTML;
}
elseif (isset($_SESSION['nombre']) && $_SESSION['descripcion'] === "usuario") {
    
}
                }
            } 
            else {
                echo <<<HTML
                <tr>
                    <td colspan="12" class="text-center">No hay categorias registradas.</td>
                </tr>
HTML;
            }
    
            echo <<<HTML
                        </tbody>
                    </table>
                </div>
    
               
            </div>
            <div class="text-center my-4">
                    <a href="categorias.php?accion=aggcategorias" class="btn btn-outline-secondary">
                        <i class="fas fa-user-plus"></i> Agregar Categorias
                    </a>
                </div>
    
                <!-- Botón para volver al inicio -->
                <div class="text-center">
                    <a href="../index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-house"></i> Volver al inicio
                    </a>
                </div>
    
            <script src="../js/buscar.js">
            </script>
            <script>
                $(document).ready(function() {
                    $('#category-filter').change(function() {
                        const categoria = $(this).val();
                        // Implementar filtro dinámico por categoría
                        console.log('Filtrar por:', categoria);
                    });
                });
            </script>
HTML;
        } else {
            echo <<<HTML
            <div class="container mt-5 text-center">
                <p>Para continuar, inicia sesión.</p>
                <a href="../vistas/login.php?accion=login-html" class="btn btn-primary">Iniciar sesión</a>
    
            </div>
            
HTML;
        }
    
        echo <<<HTML
          <!-- Botón para agregar usuarios -->
          
        </body>
        </html>
HTML;
}

function Formulario_categorias(){
    session_start();

    if (!isset($_SESSION["nombre"])) {
        header("Location: ../vistas/login.php?accion=login-html&mensaje=inicia sesion para continuar");
        exit();
    }

    $usuario_actual = $_SESSION["nombre"];
    //echo "rol : ".$_SESSION['descripcion'];
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!--<link rel="stylesheet" href="../../css/cargando.css">
    <link rel="shortcut icon" href="../../fotos/agregar-usuario.png" type="image/x-icon">-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!--<script src="../js/cargando.js"></script>-->
    <title>Registro de Categorias</title>
</head>
<body>
HTML;

    if (isset($_SESSION["nombre"])) {
        echo <<<HTML
        <div>
        <span class="me-3">Usuario: <strong>$usuario_actual
                                    </strong>
        </span>
        <a href="../vistas/usuarios.php?accion=cerrar" class="btn btn-danger btn-sm">Cerrar sesión</a>
    </div>
HTML;
    }
        echo <<<HTML
<div id="loading" style="display: none;">Cargando...

</div>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
                        
                    </div>                                          
    <div class="row">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            
            <div class="card">
                <div class="card-content">
                    <h4 class="center-align grey-text">Registro de Categorias</h4>
                    <form id="myForm" onsubmit="showLoading()" action="categorias.php?accion=registrar" method="post">
                        

                        <div class="input-field">
                            <input id="nombre" type="text" name="nombre" required>
                            <label for="nombre">Nombre de la categoria</label>
                        </div>
                        <div class="center-align">
                            <button class="btn waves-effect waves-light" type="submit" name="registro">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-action center-align">
                    <form action="./categorias.php?accion=vercategorias" onsubmit="showLoading()" method="post">
                        <button class="btn-flat waves-effect">
                            <i class="material-icons left">Categorias</i> 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
     /*} elseif ((!isset($_SESSION["cargo_id"]))) {
         echo <<<HTML
 <div class="container">
     <div class="row">
         <div class="col s12">
             <p class="center-align">Para continuar, inicia sesión.</p>
             <div class="center-align">
                 <a href="../pagina-principal/login.php?accion=login" class="btn waves-effect waves-light">
                     Iniciar sesión
                 </a>
             </div>
         </div>
     </div>
 </div>
HTML;
     }*/

    echo <<<HTML
<div class="container center-align">
    <form action="../index.php" onsubmit="showLoading()" method="post">
        <button class="btn-flat waves-effect">
            <i class="material-icons left">Inicio</i> 
        </button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
</body>
</html>
HTML;
}

?>