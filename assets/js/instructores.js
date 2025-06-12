document.addEventListener("DOMContentLoaded", () => {
    cargarInstructores();
});

function cargarInstructores() {
    fetch("/proyecto-sena/functions/functions_instructores.php")
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("instructores-container");
            container.innerHTML = "";

            data.forEach(instructor => {
                const estado = instructor.Tipo_instructor === "Inactivo" ? "Inactivo" : "Activo";
                const activo = estado === "Activo";
                const claseCard = activo ? "" : "disabled";
                const botonTexto = activo ? "Deshabilitar" : "Habilitar";
                const claseBoton = activo ? "btn-deshabilitar" : "btn-habilitar";
                const jefeFicha = instructor.Ficha ? "Sí" : "No";

                container.innerHTML += `
                    <div class="instructor-card ${claseCard}" data-id="${instructor.Id_instructor}">
                        <div class="instructor-content">
                            <div class="avatar"><div class="avatar-icon">👤</div></div>
                            <div class="instructor-info">
                                <div class="instructor-header">
                                    <h3 class="instructor-name">${instructor.nombre} ${instructor.apellido}</h3>
                                    <button class="btn-estado ${claseBoton}" onclick="toggleInstructor(this)">
                                        ${botonTexto}
                                    </button>
                                </div>
                                <div class="instructor-details">
                                    <div class="detail-item"><label>T. Documento</label><span>${instructor.T_Documento}</span></div>
                                    <div class="detail-item"><label>Num. Documento</label><span>${instructor.N_Documento}</span></div>
                                    <div class="detail-item"><label>Correo Instructor</label><span>${instructor.Email}</span></div>
                                    <div class="detail-item"><label>N° Teléfono</label><span>${instructor.N_Telefono}</span></div>
                                    <div class="detail-item estado-item"><label>Estado</label><span>${estado}</span></div>
                                    <div class="detail-item"><label>Jefe de ficha</label><span>${jefeFicha}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error("Error al cargar instructores:", error);
            alert("No se pudieron cargar los instructores.");
        });
}

function toggleInstructor(button) {
    const card = button.closest(".instructor-card");
    const estadoSpan = card.querySelector(".estado-item span");
    const id = card.getAttribute("data-id");

    const esInactivo = card.classList.contains("disabled");
    const accion = esInactivo ? "Habilitar" : "Deshabilitar";

    // Actualización visual inmediata
    if (esInactivo) {
        card.classList.remove("disabled");
        button.textContent = "Deshabilitar";
        button.classList.remove("btn-habilitar");
        button.classList.add("btn-deshabilitar");
        estadoSpan.textContent = "Activo";
    } else {
        card.classList.add("disabled");
        button.textContent = "Habilitar";
        button.classList.remove("btn-deshabilitar");
        button.classList.add("btn-habilitar");
        estadoSpan.textContent = "Inactivo";
    }

    // Solicitud al backend
    fetch("control_instructores.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}&accion=${accion}`
    })
    .then(res => res.json())
    .then(response => {
        if (response.status !== "success") {
            alert("No se pudo cambiar el estado.");
            cargarInstructores(); // Restaurar si falla
        }
    });
}