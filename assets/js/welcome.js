let currentSlide = 0;
const totalSlides = 3;

// Cambia las rutas de las imágenes según tus archivos reales
const slideData = [
    { name: 'Foto 1', img: 'assets/img/prueba.jpg' },
    { name: 'Foto 2', img: 'assets/img/luna 2.png' },
    { name: 'Foto 3', img: 'assets/img/JKE.png' }
];

const sideImages = document.querySelectorAll('.side-image');
const mainImage = document.querySelector('.main-image');
const dots = document.querySelectorAll('.pagination-dot');
const prevBtn = document.querySelector('.nav-button.prev');
const nextBtn = document.querySelector('.nav-button.next');

function updateCarousel() {
    // Imagen principal
    mainImage.style.backgroundImage = `url('${slideData[currentSlide].img}')`;
    mainImage.title = slideData[currentSlide].name;

    // Imágenes laterales
    const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
    const nextIndex = (currentSlide + 1) % totalSlides;
    sideImages[0].style.backgroundImage = `url('${slideData[prevIndex].img}')`;
    sideImages[1].style.backgroundImage = `url('${slideData[nextIndex].img}')`;

    // Puntos de paginación
    dots.forEach((dot, idx) => {
        dot.style.backgroundColor = idx === currentSlide ? '#39A900' : '#999';
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

prevBtn.addEventListener('click', previousSlide);
nextBtn.addEventListener('click', nextSlide);

dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
        currentSlide = idx;
        updateCarousel();
    });
});

// Inicializa el carrusel
updateCarousel();

// Auto-play (opcional)
setInterval(nextSlide, 5000);