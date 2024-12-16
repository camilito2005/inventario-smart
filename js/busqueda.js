console.log("busqueda");
//function buscarUsuarios() {
    $("#search").keyup(function () {
        let search = $("#search").val(); // Captura el valor del input
        console.log(search);
        if (search) {
            $.ajax({
                url: "./usuarios.php?accion=buscar", // URL con la acción "buscar"
                type: "POST",
                data: { search },
                success: function (response) {
                    console.log(response);
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
                                            <td>${task.dni}</td>
                                            <td>${task.nombre}</td>
                                            <td>${task.apellidos}</td>
                                            <td>${task.telefono}</td>
                                            <td>${task.direccion}</td>
                                            <td>${task.correo}</td>
                                            <td>${task.contraseña}</td>
                                            <td>${task.cargo}</td>
                                            <td>
                                                <a href='usuarios.php?accion=modificar&id=${encodeURIComponent(idencriptado)}'>
                                                    <i class='fa-solid fa-pen'>m</i>
                                                </a>
                                                <a href='usuarios.php?accion=eliminar&id=${encodeURIComponent(idencriptado)}' onclick='return pregunta()'>
                                                    <i class='fa-solid fa-trash'>e</i>
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                });
                                $("#resultados-usuarios").html(template); // Insertar la tabla en el HTML
                            } else {
                                $("#resultados-usuarios").html("<tr><td colspan='10'>No se encontraron resultados</td></tr>");
                            }
                        } else {
                            console.log("La respuesta está vacía 2");
                            $("#resultados-usuarios").html("<tr><td colspan='10'>No se encontraron resultados</td></tr>");
                        }
                    } catch (e) {
                        console.error("Error al parsear JSON:", e);
                        console.log("Respuesta del servidor:", response);
                        $("#resultados-usuarios").html("<tr><td colspan='10'>Error al procesar la solicitud</td></tr>");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                },
            });
        }
    });
//}

/*$(document).ready(function () {
    // Obtén el valor de la acción desde un atributo o variable
    let accion = "buscar"; // Este valor puede venir de un input, query string o cualquier otra fuente

    if (accion === "buscar") {
        buscarUsuarios(); // Llamamos a la función si la acción es "buscar"
    }
});*/
