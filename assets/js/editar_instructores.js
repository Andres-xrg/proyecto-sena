function abrirModal(instructor) {
    document.getElementById('editId').value = instructor.Id_instructor;
    document.getElementById('editNombre').value = instructor.nombre;
    document.getElementById('editApellido').value = instructor.apellido;
    document.getElementById('editEmail').value = instructor.Email;
    document.getElementById('editTipoDocumento').value = instructor.T_documento;
    document.getElementById('editNumeroDocumento').value = instructor.N_Documento;
    document.getElementById('editTelefono').value = instructor.N_Telefono;

    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

function validarFormulario() {
    // Validaciones adicionales si deseas
    return true;
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function(event) {
    const modal = document.getElementById('modalEditar');
    if (event.target === modal) {
        cerrarModal();
    }
};
