// Alternar apertura de tarjetas
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

// Redirecciones a vistas
function competencias_aprendiz() {
    window.location.href = 'index.php?page=components/competencias/competencias';
}

function competencias_generales() {
    window.location.href = 'index.php?page=components/competencias/juicios-evaluativos-comp';
}

// Cambiar estado "Traslado"
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

// Cambiar estado "Activo"
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

// Abrir modal de generación de reporte
function generarReporte() {
    abrirModal();
}

// Función para mostrar el modal
function abrirModal() {
    const modal = document.getElementById('modalReporte');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('modalReporte');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") {
        cerrarModal();
    }
});

// Al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Iconos de chevron iniciales
    const chevronComp = document.getElementById('chevron-competencias');
    const chevronTrans = document.getElementById('chevron-transversales');
    if (chevronComp) chevronComp.classList.add('rotated');
    if (chevronTrans) chevronTrans.classList.add('rotated');

    // Comportamiento para iconos de colapso
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(function(collapseEl) {
        collapseEl.addEventListener('show.bs.collapse', function() {
            const icon = document.querySelector(`[data-bs-target="#${this.id}"] .collapse-icon`);
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });

        collapseEl.addEventListener('hide.bs.collapse', function() {
            const icon = document.querySelector(`[data-bs-target="#${this.id}"] .collapse-icon`);
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });
});
