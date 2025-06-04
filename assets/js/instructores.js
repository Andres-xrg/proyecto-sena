function registrarInstructor() {
            alert('Abriendo formulario para registrar nuevo instructor');
        }

        function toggleInstructor(button, nombreInstructor) {
            const card = button.closest('.instructor-card');
            
            if (button.textContent.trim() === 'Deshabilitar') {
                // Cambiar a estado deshabilitado
                button.textContent = 'Habilitar';
                button.className = 'btn-estado btn-habilitar';
                card.classList.add('disabled');
                
                console.log('Instructor ' + nombreInstructor + ' deshabilitado');
            } else {
                // Cambiar a estado habilitado
                button.textContent = 'Deshabilitar';
                button.className = 'btn-estado btn-deshabilitar';
                card.classList.remove('disabled');
                
                console.log('Instructor ' + nombreInstructor + ' habilitado');
            }
        }

        function toggleInstructor(button) {
    const card = button.closest('.instructor-card');
    const estadoSpan = card.querySelector('.estado-item span');

    if (card.classList.contains('disabled')) {
        // Habilitar
        card.classList.remove('disabled');
        button.textContent = 'Deshabilitar';
        button.classList.remove('btn-habilitar');
        button.classList.add('btn-deshabilitar');
        estadoSpan.textContent = 'Activo';
    } else {
        // Deshabilitar
        card.classList.add('disabled');
        button.textContent = 'Habilitar';
        button.classList.remove('btn-deshabilitar');
        button.classList.add('btn-habilitar');
        estadoSpan.textContent = 'Inactivo';
    }
}