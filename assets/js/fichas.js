document.getElementById('searchInput').addEventListener('keyup', function () {
    const filtro = this.value.toLowerCase();
    const estudiantes = document.querySelectorAll('.student-card');

    estudiantes.forEach(card => {
        const nombre = card.querySelector('.student-name').textContent.toLowerCase();
        card.style.display = nombre.includes(filtro) ? '' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.querySelector('.update-form input[type="file"]');
    const updateButton = document.querySelector('.btn-actualizar-juicios');

    // Oculta el botón si no hay archivo seleccionado
    if (fileInput && updateButton) {
        updateButton.style.display = 'none';

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                updateButton.style.display = 'inline-block';
            } else {
                updateButton.style.display = 'none';
            }
        });
    }
});
