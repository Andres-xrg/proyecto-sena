const btnModoOscuro = document.getElementById('modoOscuroBtn');
const icono = btnModoOscuro.querySelector('i');

// Aplicar modo guardado al cargar la página
window.addEventListener('DOMContentLoaded', () => {
  const modoGuardado = localStorage.getItem('modo');
  if (modoGuardado === 'oscuro') {
    document.body.classList.add('dark');
    icono.classList.remove('fa-moon');
    icono.classList.add('fa-sun');
  } else {
    document.body.classList.remove('dark');
    icono.classList.remove('fa-sun');
    icono.classList.add('fa-moon');
  }
});

btnModoOscuro.addEventListener('click', function () {
  document.body.classList.toggle('dark');

  const esOscuro = document.body.classList.contains('dark');

  if (esOscuro) {
    icono.classList.remove('fa-moon');
    icono.classList.add('fa-sun');
    localStorage.setItem('modo', 'oscuro');
  } else {
    icono.classList.remove('fa-sun');
    icono.classList.add('fa-moon');
    localStorage.setItem('modo', 'claro');
  }
});

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Agregar clase 'has-dropdown' a los elementos de navegación que tienen submenús
    const navLinks = document.querySelectorAll('.nav-header a');
    
    // Crear botón de menú hamburguesa
    const navHeader = document.querySelector('.nav-header');
    const menuToggle = document.createElement('button');
    menuToggle.className = 'menu-toggle';
    menuToggle.setAttribute('aria-label', 'Abrir menú');
    menuToggle.innerHTML = '<span></span><span></span><span></span>';
    
    // Insertar el botón antes del nav-header
    const headerContainer = document.querySelector('.header-container');
    headerContainer.appendChild(menuToggle);
    
    // Función para alternar el menú
    function toggleMenu() {
        menuToggle.classList.toggle('active');
        navHeader.classList.toggle('active');
    }
    
    // Agregar evento de clic al botón de menú
    menuToggle.addEventListener('click', toggleMenu);
    
    // Cerrar el menú al hacer clic en un enlace
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            menuToggle.classList.remove('active');
            navHeader.classList.remove('active');
        });
    });
    
    // Convertir enlaces con submenús en desplegables
    navLinks.forEach(function(link) {
        // Verificar si el enlace contiene "programas_formacion" o "instructores"
        if (link.href.includes('programas_formacion')) {
            link.classList.add('has-dropdown');
            
            // Crear el menú desplegable
            const dropdown = document.createElement('div');
            dropdown.className = 'dropdown-menu';
            
            // Agregar elementos al menú desplegable
            
            // Agregar el menú desplegable al enlace
            link.appendChild(dropdown);
            
            // Para dispositivos móviles
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.classList.toggle('active');
                }
            });
        }
        
        if (link.href.includes('instructores')) {
            link.classList.add('has-dropdown');
            
            // Crear el menú desplegable
            const dropdown = document.createElement('div');
            dropdown.className = 'dropdown-menu';
            
            // Agregar elementos al menú desplegable
            
            // Agregar el menú desplegable al enlace
            link.appendChild(dropdown);
            
            // Para dispositivos móviles
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.classList.toggle('active');
                }
            });
        }
    });
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-header') && 
            !e.target.closest('.menu-toggle') && 
            navHeader.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Ajustar menú en resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && navHeader.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Código existente para el modo oscuro
    const modoOscuroBtn = document.getElementById('modoOscuroBtn');
    if (modoOscuroBtn) {
        modoOscuroBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            
            // Cambiar el icono según el modo
            const moonIcon = modoOscuroBtn.querySelector('i');
            if (document.body.classList.contains('dark')) {
                moonIcon.classList.remove('fa-moon');
                moonIcon.classList.add('fa-sun');
            } else {
                moonIcon.classList.remove('fa-sun');
                moonIcon.classList.add('fa-moon');
            }
            
            // Guardar preferencia en localStorage
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        });
        
        // Verificar preferencia guardada
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
            const moonIcon = modoOscuroBtn.querySelector('i');
            moonIcon.classList.remove('fa-moon');
            moonIcon.classList.add('fa-sun');
        }
    }
});