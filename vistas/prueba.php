<?php 

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
                <form method="get" action="prueba.php?accion=ver&">
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
?>











function Mostrarequipos()
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

if ($filtro_categorias) {
$consulta .= " WHERE d.categoria_id = $filtro_categorias";
}

$consulta .= " ORDER BY dispositivos.dispositivo_id LIMIT $registros_por_pagina OFFSET $offset";

echo $consulta;
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
    <script>
        $(document).ready(function () {
            $('#search').on('keyup', function () {
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
    </body>
    </html>
HTML;
}


