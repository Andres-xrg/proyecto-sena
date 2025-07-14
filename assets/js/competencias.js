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

// CORREGIDO: Manejo completo de flechas y colapsos
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando manejo de flechas...');
    
    // Función para actualizar el estado de una flecha
    function actualizarFlecha(trigger, isOpen) {
        const icon = trigger.querySelector('.collapse-icon, .fa-chevron-down, .fa-chevron-up, i[class*="chevron"]');
        if (icon) {
            // Limpiar todas las clases de chevron
            icon.classList.remove('fa-chevron-down', 'fa-chevron-up');
            
            if (isOpen) {
                icon.classList.add('fa-chevron-up');
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.classList.add('fa-chevron-down');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    }
    
    // Función para sincronizar el estado inicial de todas las flechas
    function sincronizarEstadoInicial() {
        const allCollapses = document.querySelectorAll('.collapse');
        allCollapses.forEach(function(collapse) {
            const isShown = collapse.classList.contains('show');
            const targetId = collapse.id;
            const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
            
            if (trigger) {
                actualizarFlecha(trigger, isShown);
            }
        });
    }
    
    // Configurar event listeners para todos los elementos collapse
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(function(collapseEl) {
        // Evento cuando se muestra el colapso
        collapseEl.addEventListener('show.bs.collapse', function() {
            const targetId = this.id;
            const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
            if (trigger) {
                actualizarFlecha(trigger, true);
            }
        });
        
        // Evento cuando se oculta el colapso
        collapseEl.addEventListener('hide.bs.collapse', function() {
            const targetId = this.id;
            const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
            if (trigger) {
                actualizarFlecha(trigger, false);
            }
        });
        
        // Evento cuando termina de mostrarse
        collapseEl.addEventListener('shown.bs.collapse', function() {
            const targetId = this.id;
            const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
            if (trigger) {
                actualizarFlecha(trigger, true);
            }
        });
        
        // Evento cuando termina de ocultarse
        collapseEl.addEventListener('hidden.bs.collapse', function() {
            const targetId = this.id;
            const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
            if (trigger) {
                actualizarFlecha(trigger, false);
            }
        });
    });
    
    // Manejar clicks directos en los triggers
    const allTriggers = document.querySelectorAll('[data-bs-toggle="collapse"]');
    allTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            const targetSelector = this.getAttribute('data-bs-target');
            const target = document.querySelector(targetSelector);
            
            if (target) {
                // Pequeño delay para que Bootstrap procese el cambio
                setTimeout(() => {
                    const isShown = target.classList.contains('show');
                    actualizarFlecha(this, isShown);
                }, 50);
            }
        });
    });
    
    // Sincronizar estado inicial después de un pequeño delay
    setTimeout(sincronizarEstadoInicial, 100);
    
    // Re-sincronizar después de que Bootstrap termine de cargar
    setTimeout(sincronizarEstadoInicial, 500);
    
    // Iconos de chevron iniciales para elementos específicos
    const chevronComp = document.getElementById('chevron-competencias');
    const chevronTrans = document.getElementById('chevron-transversales');
    if (chevronComp) chevronComp.classList.add('rotated');
    if (chevronTrans) chevronTrans.classList.add('rotated');
});

// Función adicional para debug
function debugFlechas() {
    console.log('=== DEBUG FLECHAS ===');
    const collapses = document.querySelectorAll('.collapse');
    collapses.forEach(collapse => {
        const id = collapse.id;
        const isShown = collapse.classList.contains('show');
        const trigger = document.querySelector(`[data-bs-target="#${id}"]`);
        const icon = trigger ? trigger.querySelector('.collapse-icon, .fa-chevron-down, .fa-chevron-up, i[class*="chevron"]') : null;
        
        console.log(`ID: ${id}, Shown: ${isShown}, Trigger: ${!!trigger}, Icon: ${!!icon}`);
        if (icon) {
            console.log(`  Icon classes: ${icon.className}`);
            console.log(`  Transform: ${icon.style.transform}`);
        }
    });
}

// Exponer función de debug globalmente para testing
window.debugFlechas = debugFlechas;