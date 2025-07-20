// Esperar que cargue completamente el DOM
window.addEventListener('DOMContentLoaded', () => {
    // Modales
    const modalPrograma = document.getElementById('modalPrograma');
    const modalEditarPrograma = document.getElementById('modalEditarPrograma');

    // Asegurarse de ocultar modales al inicio
    if (modalPrograma) {
        modalPrograma.classList.add('hidden');
        modalPrograma.style.display = 'none';
    }
    if (modalEditarPrograma) {
        modalEditarPrograma.classList.add('hidden');
        modalEditarPrograma.style.display = 'none';
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function (e) {
        if (e.target === modalPrograma) cerrarModalPrograma();
        if (e.target === modalEditarPrograma) cerrarModalEditar();
    });

    // Agregar eventos a todos los botones Editar
    const botonesEditar = document.querySelectorAll('.btn-editar-programa');
    botonesEditar.forEach(boton => {
        boton.addEventListener('click', function () {
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            const tipo = this.dataset.tipo;

            abrirModalEditar(id, nombre, tipo);
        });
    });
});

// Abrir modal de nuevo programa
function abrirModalPrograma() {
    const modal = document.getElementById('modalPrograma');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    toggleMenu();
}

// Cerrar modal de nuevo programa
function cerrarModalPrograma() {
    const modal = document.getElementById('modalPrograma');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Abrir modal de edición con datos cargados
function abrirModalEditar(id, nombre, tipo) {
    document.getElementById('editIdPrograma').value = id;
    document.getElementById('editNombrePrograma').value = nombre;
    document.getElementById('editTipoPrograma').value = tipo;

    const modal = document.getElementById('modalEditarPrograma');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

// Cerrar modal de edición
function cerrarModalEditar() {
    const modal = document.getElementById('modalEditarPrograma');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Menú desplegable superior
function toggleMenu() {
    const menu = document.getElementById('menuOptions');
    menu.classList.toggle('show');
}

function toggleDropdown() {
    document.getElementById('dropdownOptions').classList.toggle('show');
    document.getElementById('dropdownFiltro').classList.toggle('active');
}

function seleccionarFiltro(tipo) {
    document.getElementById('tipoHidden').value = tipo;
    document.getElementById('selectedOption').textContent = tipo ? tipo.charAt(0).toUpperCase() + tipo.slice(1) : 'Todos';
    document.querySelector('.filtro-barra form').submit();
}
