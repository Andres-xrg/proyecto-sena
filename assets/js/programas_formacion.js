function abrirModalPrograma() {
    document.getElementById('modalPrograma').classList.remove('hidden');
    document.getElementById('modalPrograma').style.display = 'flex';
    toggleMenu();
}

function cerrarModalPrograma() {
    document.getElementById('modalPrograma').classList.add('hidden');
    document.getElementById('modalPrograma').style.display = 'none';
}

window.onclick = function(e) {
    const modal = document.getElementById('modalPrograma');
    if (e.target === modal) {
        cerrarModalPrograma();
    }
}

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
