<?php
function Masventas() {
    include_once "../conexion.php";
    $conexion = Conexion();

    $query = "
        SELECT 
            p.nombre AS nombre_producto,
            SUM(f.stock) AS total_vendido
        FROM 
            facturas f
        JOIN 
            productos p ON f.producto_id = p.id
        GROUP BY 
            p.nombre
        ORDER BY 
            total_vendido DESC
        LIMIT 10;
    ";
    //echo $query;

    $resultado = pg_query($conexion, $query);
    if (!$resultado) {
        die("Error en la consulta: " . pg_last_error($conexion));
    }

    $productos_mas_vendidos = pg_fetch_all($resultado);
    
    // Preparar datos para Chart.js
    $productos = [];
    $totales = [];
    
    if ($productos_mas_vendidos) {
        foreach ($productos_mas_vendidos as $producto) {
            $productos[] = $producto['nombre_producto'];
            $totales[] = $producto['total_vendido'];
        }
    }

    // Convertir los datos a JSON
    $productos_json = json_encode($productos);
    $totales_json = json_encode($totales);

    pg_close($conexion);

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Estadísticas de Productos Más Vendidos</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <h2>Top Productos Más Vendidos</h2>
        <canvas id="chartProductos"></canvas>
        <script>
            var ctx = document.getElementById('chartProductos').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Puedes cambiar a 'line', 'pie', etc.
                data: {
                    labels: $productos_json,
                    datasets: [{
                        label: 'Total Vendido',
                        data: $totales_json,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
                <form id="myForm" action="../../index.php" onsubmit="showLoading()" method="post">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fa-solid fa-house"></i> Inicio
        </button>
    </form>
    </body>
    </html>
HTML;
}
function EquiposPorMarca() {
    include_once "../conexion.php";
    $conexion = Conexion();

    // Consulta SQL: Contar equipos agrupados por marca
    $query = "
        SELECT 
            dispositivo_marca,
            COUNT(*) AS total_equipos
        FROM 
            dispositivos
        GROUP BY 
            dispositivo_marca
        ORDER BY 
            total_equipos DESC;
    ";

    // Ejecutar la consulta
    $resultado = pg_query($conexion, $query);
    if (!$resultado) {
        die("Error en la consulta: " . pg_last_error($conexion));
    }

    // Procesar los resultados
    $equipos_por_marca = pg_fetch_all($resultado);

    // Preparar datos para Chart.js
    $marcas = [];
    $totales = [];

    if ($equipos_por_marca) {
        foreach ($equipos_por_marca as $equipo) {
            $marcas[] = $equipo['marca'];
            $totales[] = $equipo['total_equipos'];
        }
    }

    // Convertir los datos a JSON
    $marcas_json = json_encode($marcas);
    $totales_json = json_encode($totales);

    pg_close($conexion);

    // Generar la vista HTML con la gráfica
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Estadísticas de Equipos por Marca</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-4">
            <h2 class="text-center mb-4">Cantidad de Equipos por Marca</h2>
            <canvas id="chartEquipos"></canvas>
            <script>
                var ctx = document.getElementById('chartEquipos').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: $marcas_json,
                        datasets: [{
                            label: 'Cantidad de Equipos',
                            data: $totales_json,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            </script>
            <form id="myForm" action="../index.php" onsubmit="showLoading()" method="post">
                <button class="btn btn-outline-secondary mt-3" type="submit">
                    <i class="fa-solid fa-house"></i> Inicio
                </button>
            </form>
        </div>
    </body>
    </html>
HTML;
}


//5. Contar dispositivos por categoría
$consulta = <<<SQL
SELECT c.nombre AS categoria, COUNT(d.dispositivo_id) AS cantidad_dispositivos
FROM categorias c
LEFT JOIN dispositivos d ON c.categoria_id = d.categoria_id
GROUP BY c.nombre;
SQL;

?>