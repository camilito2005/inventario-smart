console.log("buscar");
function buscarUsuarios() {
    $("#search").keyup(function () {
        let search = $("#search").val(); // Captura el valor del input
        console.log(search);
        if (search) {
            $.ajax({
                url: "./equipos.php?accion=buscar", // URL con la acción "buscar"
                type: "POST",
                data: { search },
                success: function (response) {
                    console.log("respuesta: ",response);
                    try {
                        if (response) {
                            let tasks = JSON.parse(response);
                            if (tasks.length > 0) {
                                let template = "";
                                tasks.forEach((task) => {
                                    // Encripta el ID usando btoa (base64)
                                    let idencriptado = btoa(task.id);
                                    template += `
                                        <tr> 
                                            <td>${task.id}</td>
                                            <td>${task.nombre}</td>
                                            <td>${task.marca}</td>
                                            <td>${task.modelo}</td>
                                            <td>${task.ram}</td>
                                            <td>${task.procesador}</td>
                                            <td>${task.almacenamiento}</td>
                                            <td>${task.dir_mac}</td>
                                            <td>${task.perifericos}</td>
                                            <td>${task.fecha_registro}</td>
                                            <td>${task.fecha_modificacion}</td>
                                            <td>${task.observacion}</td>
                                            <td>${task.contraseña}</td>
                                            <td>
                                                <a href='equipos.php?accion=modificar&id=${encodeURIComponent(idencriptado)}'>
                                                    <i class='fa-solid fa-pen'>modificar</i>
                                                </a>
                                                <a href='productos.php?accion=eliminar&id=${encodeURIComponent(idencriptado)}' onclick='return pregunta()'>
                                                    <i class='fa-solid fa-trash'>eliminar</i>
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                });
                                $("#resultados-equipos").html(template); // Insertar la tabla en el HTML
                            } else {
                                $("#resultados-equipos").html("<tr><td colspan='10'>No se encontraron resultados</td></tr>");
                            }
                        } else {
                            console.log("La respuesta está vacía 1");
                            $("#resultados-equipos").html("<tr><td colspan='10'>No se encontraron resultados</td></tr>");
                        }
                    } catch (e) {
                        console.error("Error al parsear JSON:", e);
                        console.log("Respuesta del servidor:", response);
                        $("#resultados-equipos").html("<tr><td colspan='10'>Error al procesar la solicitud</td></tr>");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                },
            });
        }
    });
    }
    
    $(document).ready(function () {
        // Obtén el valor de la acción desde un atributo o variable
        let accion = "buscar"; // Este valor puede venir de un input, query string o cualquier otra fuente
    
        if (accion === "buscar") {
            buscarUsuarios(); // Llamamos a la función si la acción es "buscar"
        }
    });
    