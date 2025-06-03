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
