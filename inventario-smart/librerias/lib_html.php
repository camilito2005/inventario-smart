<?php

function ingresar_equipos()
{
    session_start();
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="../../css/cargando.css">
    <link rel="shortcut icon" href="../../fotos/agregar-usuario.png" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../js/cargando.js"></script>
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
                            <label for="mrca">MARCA</label>
                        </div>
                        <div class="input-field">
                            <input id="modelo" type="text" name="modelo" required>
                            <label for="modelo">MODELO</label>
                        </div>

                        <div class="input-field">
                            <input id="ram" type="text" name="ram" required>
                            <label for="ram">MEMORIA RAM</label>
                        </div>

                        <div class="input-field">
                            <input id="procesador" type="text" name="procesador" required>
                            <label for="procesador">PROCESADOR</label>
                        </div>

                        <div class="input-field">
                            <input id="almacenamiento" type="text" name="almacenamiento" required>
                            <label for="almacenamiento">ALMACENAMIENTO</label>
                        </div>

                        <div class="input-field">
                            <input id="dir-mac" type="text" name="dir-mac" required>
                            <label for="dir-mac">DIRECCION MAC</label>
                        </div>

                        <div class="input-field">
                            <input id="perifericos" type="text" name="perifericos" required>
                            <label for="perifericos">PERIFERICOS</label>
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
    <form action="../../index.php" onsubmit="showLoading()" method="post">
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
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <title>Tabla de equipos</title>
    </head>
HTML;

    // if (isset($_SESSION["correo"])) {
        echo <<<HTML
        <body>
            <div class="container mt-4">
                <h3 class="text-center text-secondary">equipos</h3>

                <!-- Barra de búsqueda -->
                <div class="input-group my-4 justify-content-center">
                    <input type="search" id="search" class="form-control w-50" placeholder="Buscar...">
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>MARCA</th>
                                <th>MODELO</th>
                                <th>MEMORIA RAM</th>
                                <th>PROCESADOR</th>
                                <th>ALMACENAMIENTO</th>
                                <th>DIRECCION MAC</th>
                                <th>PERIFERICOS</th>
                                <th>FECHA INGRESO</th>
                                <th>FECHA MODIFICACION</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultados-usuarios">
HTML;

        include_once "../conexion.php";
        $conexion = Conexion();
        $consulta1 = "SELECT * 
                      FROM dispositivos ";
        $query = pg_query($conexion, $consulta1);
        $usuarios = pg_fetch_all($query);

        if ($usuarios) {
            foreach ($usuarios as $fila) {
                $id_encriptado = base64_encode($fila['dispositivo_id']);
                echo <<<HTML
                <tr>
                    <td>{$fila['dispositivo_id']}</td>
                    <td>{$fila['dispositivo_nombre_usuario']}</td>
                    <td>{$fila['dispositivo_marca']}</td>
                    <td>{$fila['dispositivo_modelo']}</td>
                    <td>{$fila['dispositivo_ram']}</td>
                    <td>{$fila['dispositivo_procesador']}</td>
                    <td>{$fila['dispositivo_almacenamiento']}</td>
                    <td>{$fila['dispositivo_direccion_mac']}</td>
                    <td>{$fila['dispositivo_perifericos']}</td>
                    <td>{$fila['fecha_registro']}</td>
HTML;
if (!empty($fila['fecha_modificacion'])) {
                    echo "<td>{$fila['fecha_modificacion']}</td>";
                }else {
                    echo "<td>no hay fecha de modificacion</td>";
                }
                    echo <<<HTML
                    <td>
                        <a href="equipos.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                            <i class="fas fa-pen">Modificar</i>
                        </a>
                        <a href="equipos.php?accion=eliminar&id={$id_encriptado}" onclick="return pregunta()" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash">Eliminar</i>
                        </a>
                    </td>
                </tr>
                <script>
                    function pregunta() {
                        pre = confirm("¿estas seguro que desea eliminar el registro?");
                        return pre;
                    }
                </script>
HTML;
            }
        } else {
            echo <<<HTML
            <tr>
                <td colspan="10" class="text-center">No hay equipos registrados.</td>
            </tr>
HTML;
        }

        echo <<<HTML
                        </tbody>
                    </table>
                </div>

                <!-- Botón para agregar usuarios -->
                <div class="text-center my-4">
                    <form action="./equipos.php?accion=aggequipos" onsubmit="showLoading()" method="post">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-user-plus"></i> Agregar equipos
                        </button>
                    </form>
                </div>

                <!-- Botón para volver al inicio -->
                <div class="text-center">
                    <form action="../index.php" onsubmit="showLoading()" method="post">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-house"></i> Inicio
                        </button>
                    </form>
                </div>
            </div>

            <!-- Scripts -->
            <script src="../../js/buscador.js"></script>
            <script src="../../js/pregunta.js"></script>
        </body>
HTML;
//     } else {
//         echo <<<HTML
//         <body>
//             <div class="container mt-5 text-center">
//                 <p>Para continuar, inicia sesión.</p>
//                 <a href="../pagina-principal/login.php?accion=login" class="btn btn-primary">Iniciar sesión</a>
//             </div>
//         </body>
// HTML;
//     }

    echo <<<HTML
    </html>
HTML;
}


function Mostrarequipos00(){
    echo <<<HTML
    <a href="./equipos.php?accion=aggequipos" class="btn waves-effect waves-light">
        agrega esquipos
    </a>
HTML;
}

function Principal(){

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
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Usuarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Bienvenido al Inventario SamartInfo</h1>
            <p class="lead">Gestiona tus productos de manera fácil y eficiente.</p>
            <a href="#" class="btn btn-primary btn-lg">Explorar Inventario</a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text">Añade, edita o elimina productos de tu inventario.</p>
                        <a href="#" class="btn btn-primary">Gestionar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categorías</h5>
                        <p class="card-text">Organiza tus productos por categorías y subcategorías.</p>
                        <a href="#" class="btn btn-primary">Explorar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Genera reportes detallados sobre tu inventario.</p>
                        <a href="#" class="btn btn-primary">Ver Reportes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white text-center py-3">
        <p class="mb-0">&copy; 2024 Inventario SamartInfo. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}

function Principal000(){
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
                        <a class="nav-link" href="./vistas/equipos.php?accion=verequipos">Productos</a>
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
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="../../css/cargando.css">
    <link rel="shortcut icon" href="../../fotos/agregar-usuario.png" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../js/cargando.js"></script>
    <title>Registro de Usuarios</title>
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
                    <h4 class="center-align grey-text">Registro de Usuarios</h4>
                    <form id="myForm" onsubmit="showLoading()" action="usuarios.php?accion=registrar" method="post">
                        
                        <div class="input-field">
                            <input id="dni" type="text" name="dni" required>
                            <label for="dni">DNI</label>
                        </div>
HTML;

        if (isset($_SESSION['descripcion']) && $_SESSION['descripcion'] === "Administrador") {
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
                    <form action="/usuarios.php?accion=verusuarios" onsubmit="showLoading()" method="post">
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


function Mostrar_usuarios()
{
    session_start();
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <title>Tabla de Usuarios</title>
    </head>
HTML;

    // if (isset($_SESSION["correo"])) {
        echo <<<HTML
        <body>
            <div class="container mt-4">
                <h3 class="text-center text-secondary">Usuarios</h3>

                <!-- Barra de búsqueda -->
                <div class="input-group my-4 justify-content-center">
                    <input type="search" id="search" class="form-control w-50" placeholder="Buscar...">
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
                                <th>Contraseña</th>
                                <th>Cargo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultados-usuarios">
HTML;

        include_once "../conexion.php";
        $conexion = Conexion();
        $consulta1 = "SELECT 
        u.id, 
        u.dni, 
        u.nombre, 
        u.apellido, 
        u.telefono, 
        u.direccion, 
        u.correo, 
        u.contraseña, 
        r.descripcion AS rol
    FROM 
        usuarios u
    INNER JOIN 
        roles r 
    ON 
        u.rol_id = r.id";
    
        $query = pg_query($conexion, $consulta1);
        $usuarios = pg_fetch_all($query);

        if ($usuarios) {
            foreach ($usuarios as $fila) {
                $id_encriptado = base64_encode($fila['id']);
                echo <<<HTML
                <tr>
                    <td>{$fila['id']}</td>
                    <td>{$fila['dni']}</td>
                    <td>{$fila['nombre']}</td>
                    <td>{$fila['apellido']}</td>
                    <td>{$fila['telefono']}</td>
                    <td>{$fila['direccion']}</td>
                    <td>{$fila['correo']}</td>
                    <td>{$fila['contraseña']}</td>
                    <td>{$fila['rol']}</td>
                    <td>
                        <a href="usuarios.php?accion=modificar&id={$id_encriptado}" class="btn btn-sm btn-primary">
                            <i class="fas fa-pen">MOdificar</i>
                        </a>
                        <a href="usuarios.php?accion=eliminar&id={$id_encriptado}" onclick="return pregunta()" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash">Eliminar</i>
                        </a>
                    </td>
                </tr>
                <script>
                    function pregunta() {
    pre = confirm("¿estas seguro que desea eliminar el registro?");
    return pre;
}
                </script>
HTML;
            }
        } else {
            echo <<<HTML
            <tr>
                <td colspan="10" class="text-center">No hay usuarios registrados.</td>
            </tr>
HTML;
        }

        echo <<<HTML
                        </tbody>
                    </table>
                </div>

                <!-- Botón para agregar usuarios -->
                <div class="text-center my-4">
                    <form action="./formulario_registro.php?accion=aggusuarios" onsubmit="showLoading()" method="post">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-user-plus"></i> Agregar Usuarios
                        </button>
                    </form>
                </div>

                <!-- Botón para volver al inicio -->
                <div class="text-center">
                    <form action="../index.php" onsubmit="showLoading()" method="post">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-house"></i> Inicio
                        </button>
                    </form>
                </div>
            </div>

            <!-- Scripts -->
            <script src="../../js/buscador.js"></script>
            <script src="../../js/pregunta.js"></script>
        </body>
HTML;
//     } else {
//         echo <<<HTML
//         <body>
//             <div class="container mt-5 text-center">
//                 <p>Para continuar, inicia sesión.</p>
//                 <a href="../pagina-principal/login.php?accion=login" class="btn btn-primary">Iniciar sesión</a>
//             </div>
//         </body>
// HTML;
//     }

    echo <<<HTML
    </html>
HTML;
}




?>