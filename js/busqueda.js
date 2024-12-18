console.log("busqueda");
    // Asumiendo que el valor del rol ya está almacenado en la variable 'role'
    var role = document.getElementById('role').value;  // Si usaste un input oculto
    // O si lo pasaste directamente como una variable PHP a JavaScript:
    // var role = "<?php echo $_SESSION['descripcion']; ?>";
    function buscarUsuarios() {
        $("#search").keyup(function () {
            let search = $("#search").val(); // Captura el valor del input
            console.log(search);
            if (search) {
                $.ajax({
                    url: "./usuarios.php?accion=", // URL con la acción "buscar"
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

                                        // Aquí agregamos la condición para mostrar los botones
                                        let acciones = '';
                                        if (role === "administrador") {
                                            cargo = `<td>${task.cargo}</td>`;
                                            acciones = `
                                            <td>
                                                <a href='usuarios.php?accion=modificar&id=${encodeURIComponent(idencriptado)}'>
                                                    <i class='fa-solid fa-pen'></i> Modificar
                                                </a>
                                                <a href='usuarios.php?accion=eliminar&id=${encodeURIComponent(idencriptado)}' onclick='return pregunta()'>
                                                    <i class='fa-solid fa-trash'></i> Eliminar
                                                </a>
                                                </td>
                                            `;
                                        }else if (role === "usuario"){
                                            cargo = ``;
                                            acciones = ``;
                                        }

                                        template += `
                                            <tr> 
                                                <td>${task.id}</td>
                                                <td>${task.dni}</td>
                                                <td>${task.nombre}</td>
                                                <td>${task.apellidos}</td>
                                                <td>${task.telefono}</td>
                                                <td>${task.direccion}</td>
                                                <td>${task.correo}</td>
                                                    ${cargo}
                                                    ${acciones}
                                            </tr>
                                        `;
                                    });
                                    $("#resultados-usuarios").html(template); // Insertar la tabla en el HTML
                                } else {
                                    $("#resultados-usuarios").html("<tr><td colspan='10'>No se encontraron resultados</td></tr>");
                                }
                            } else {
                                console.log("La respuesta está vacía");
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
    }


$(document).ready(function () {
    // Obtén el valor de la acción desde un atributo o variable
    let accion = "buscar"; // Este valor puede venir de un input, query string o cualquier otra fuente

    if (accion === "buscar") {
        buscarUsuarios(); // Llamamos a la función si la acción es "buscar"
    }
});
