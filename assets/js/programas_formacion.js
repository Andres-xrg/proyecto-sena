// Esperar que cargue completamente el DOM
window.addEventListener('DOMContentLoaded', () => {
    const modalPrograma = document.getElementById('modalPrograma');
    const modalEditarPrograma = document.getElementById('modalEditarPrograma');

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

    // Botones Editar
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

// Abrir modal crear
function abrirModalPrograma() {
    const modal = document.getElementById('modalPrograma');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    toggleMenu();
}

// Cerrar modal crear
function cerrarModalPrograma() {
    const modal = document.getElementById('modalPrograma');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Abrir modal editar
function abrirModalEditar(id, nombre, tipo) {
    document.getElementById('editIdPrograma').value = id;
    document.getElementById('editNombrePrograma').value = nombre;
    document.getElementById('editTipoPrograma').value = tipo;

    const modal = document.getElementById('modalEditarPrograma');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

// Cerrar modal editar
function cerrarModalEditar() {
    const modal = document.getElementById('modalEditarPrograma');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Mostrar/ocultar menú FAB
function toggleMenu() {
    const menu = document.getElementById('menuOptions');
    menu.classList.toggle('show');
}

// Mostrar opciones de dropdown personalizado
function toggleDropdown(tipo) {
    document.getElementById('dropdownOptions' + capitalize(tipo)).classList.toggle('show');
}

// Selección de filtros con conservación de valores
function seleccionarFiltro(valor, tipo) {
    if (valor.toLowerCase() === 'todos') valor = '';

    // Actualizar el valor oculto
    document.getElementById(tipo + 'Hidden').value = valor;

    // Cambiar texto visible
    const selectedSpan = document.getElementById('selectedOption' + capitalize(tipo));
    selectedSpan.textContent = valor ? capitalize(valor) : 'Todos';

    // Obtener los otros valores seleccionados
    const tipoVal = document.getElementById('tipoHidden').value;
    const estadoVal = document.getElementById('estadoHidden').value;

    // Redirigir manualmente conservando ambos valores
    const url = new URL(window.location.origin + '/proyecto-sena/index.php');
    url.searchParams.set('page', 'components/principales/programas_formacion');
    if (tipoVal) url.searchParams.set('tipo', tipoVal);
    if (estadoVal) url.searchParams.set('estado', estadoVal);

    window.location.href = url.toString();
}

// Capitalizar texto (primera letra mayúscula)
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
