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