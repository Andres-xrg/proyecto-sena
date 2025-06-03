 function toggleCard(cardId) {
            const content = document.getElementById('content-' + cardId);
            const chevron = document.getElementById('chevron-' + cardId);
            
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                chevron.classList.remove('rotated');
            } else {
                content.classList.add('open');
                chevron.classList.add('rotated');
            }
        }

        // Initialize some sections as open
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('chevron-competencias').classList.add('rotated');
            document.getElementById('chevron-transversales').classList.add('rotated');
        });

    function competencias_aprendiz() {
    window.location.href = 'index.php?page=components/competencias/competencias';
}

    function competencias_generales() {
    window.location.href = 'index.php?page=components/competencias/juicios-evaluativos-comp';
}


function cambiarEstadoTraslado(btn) {
    if (btn.textContent.trim() === "Traslado") {
        btn.textContent = "Trasladado";
        btn.classList.remove("badge-blue");
        btn.classList.add("badge-red");
    } else {
        btn.textContent = "Traslado";
        btn.classList.remove("badge-red");
        btn.classList.add("badge-blue");
    }
}

function cambiarEstadoActivo(btn) {
    if (btn.textContent.trim() === "Activo") {
        btn.textContent = "Inactivo";
        btn.classList.remove("badge-green");
        btn.classList.add("badge-gray");
    } else {
        btn.textContent = "Activo";
        btn.classList.remove("badge-gray");
        btn.classList.add("badge-green");
    }
}
