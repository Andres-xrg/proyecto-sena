function toggleDropdown() {
    const options = document.getElementById('dropdownOptions');

    // Alternar visibilidad
    if (options.style.display === 'block') {
        options.style.display = 'none';
        localStorage.setItem('dropdownVisible', 'false');
    } else {
        options.style.display = 'block';
        localStorage.setItem('dropdownVisible', 'true');
    }
}

// Ejecutar al cargar la página para recuperar el estado guardado
window.onload = function() {
    const options = document.getElementById('dropdownOptions');
    const visible = localStorage.getItem('dropdownVisible');

    if (visible === 'true') {
        options.style.display = 'block';
    } else {
        options.style.display = 'none';
    }
}
 
function verFicha() {
            window.location.href = '../Fichas/ficha_vista.php';
        }   