let currentSlide = 0;
const totalSlides = 3;

// Elementos del DOM
const carouselTrack = document.getElementById('carouselTrack');
const dots = document.querySelectorAll('.pagination-dot');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

function updateCarousel() {
    // Mover el track
    const translateX = -currentSlide * 100;
    carouselTrack.style.transform = `translateX(${translateX}%)`;
    
    // Actualizar slides activos
    const slides = document.querySelectorAll('.carousel-slide');
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === currentSlide);
    });
    
    // Actualizar puntos de paginación
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateCarousel();
}

function previousSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateCarousel();
}

// Event listeners
prevBtn.addEventListener('click', previousSlide);
nextBtn.addEventListener('click', nextSlide);

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        currentSlide = index;
        updateCarousel();
    });
});

// Inicializar carrusel
updateCarousel();

// Auto-play (opcional)
setInterval(nextSlide, 5000);
// AGREGAR ESTO AL FINAL DE welcome.js

// Variables para los modales
let currentEditingSlide = 0;

// Función para iniciar el proceso de edición
function startEditProcess() {
    currentEditingSlide = 1;
    openModal(1);
}

// Función para abrir modal
function openModal(slideNumber) {
    document.getElementById(`editModal${slideNumber}`).classList.add('active');
    document.body.style.overflow = 'hidden';
}

// REEMPLAZA las funciones de cierre en welcome.js con estas:

// Función para cerrar completamente (para el botón X)
function closeModal(slideNumber) {
    document.getElementById(`editModal${slideNumber}`).classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Función para cancelar y pasar al siguiente (para el botón Cancelar)
function cancelAndNext(slideNumber) {
    // Cerrar el modal actual
    document.getElementById(`editModal${slideNumber}`).classList.remove('active');
    
    // Si no es el último modal (3), pasar al siguiente
    if (slideNumber < 3) {
        setTimeout(() => {
            window.openModal(slideNumber + 1); // Assuming openModal is a global function
        }, 300);
    } else {
        // Si es el modal 3, cerrar completamente
        document.body.style.overflow = 'auto';
    }
}

// Función para guardar slide
function saveSlide(slideNumber) {
    const titleEs = document.getElementById(`titleEs${slideNumber}`).value;
    const titleEn = document.getElementById(`titleEn${slideNumber}`).value;
    const descEs = document.getElementById(`descEs${slideNumber}`).value;
    const descEn = document.getElementById(`descEn${slideNumber}`).value;
    const imageFile = document.getElementById(`image${slideNumber}`).files[0];
    
    // Validar que los campos en español estén llenos
    if (!titleEs || !descEs) {
        alert('Por favor completa al menos los campos en español');
        return;
    }
    
    // Actualizar el carrusel con los datos en español
    document.getElementById(`carousel-title-${slideNumber - 1}`).textContent = titleEs;
    document.getElementById(`carousel-desc-${slideNumber - 1}`).textContent = descEs;
    
    // Si hay imagen, actualizarla
    if (imageFile) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(`carousel-img-${slideNumber - 1}`).src = e.target.result;
        };
        reader.readAsDataURL(imageFile);
    }
    
    // Enviar datos al servidor
    sendSlideData(slideNumber, titleEs, titleEn, descEs, descEn, imageFile);
    
    // Cerrar modal actual
    closeModal(slideNumber);
    
    // Abrir siguiente modal o finalizar
    if (slideNumber < 3) {
        setTimeout(() => {
            openModal(slideNumber + 1);
        }, 300);
    } else {
        alert('¡Carrusel actualizado correctamente!');
    }
}

// Función para enviar datos al servidor
function sendSlideData(slideNumber, titleEs, titleEn, descEs, descEn, imageFile) {
    const formData = new FormData();
    formData.append('slideNumber', slideNumber);
    formData.append('titleEs', titleEs);
    formData.append('titleEn', titleEn);
    formData.append('descEs', descEs);
    formData.append('descEn', descEn);
    
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    fetch('functions_actualizar_welcome.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Slide guardado:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Cerrar modales al hacer clic fuera
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-modal')) {
        const modalNumber = e.target.id.replace('editModal', '');
        closeModal(modalNumber);
    }
});

// Cerrar modales con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        for (let i = 1; i <= 3; i++) {
            if (document.getElementById(`editModal${i}`).classList.contains('active')) {
                closeModal(i);
                break;
            }
        }
    }
});

