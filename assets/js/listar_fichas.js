function verFicha(id) {
  window.location.href = `index.php?page=components/Fichas/Ficha_vista&id=${id}`;
}

function cambiarEstadoFicha(btn, idFicha, estadoActual) {
  const nuevoEstado = estadoActual === 'Activo' ? 'Inactivo' : 'Activo';
  console.log("Cambiando estado ficha ID:", idFicha, "a:", nuevoEstado);

  fetch('functions/functions_deshabilitar_ficha.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `id=${idFicha}&estado=${nuevoEstado}`
  })
  .then(response => response.json())
  .then(data => {
    console.log("Respuesta servidor:", data);
    if (data.success) {
      btn.innerText = nuevoEstado === 'Activo' ? 'Deshabilitar' : 'Habilitar';
      btn.setAttribute('onclick', `cambiarEstadoFicha(this, ${idFicha}, '${nuevoEstado}')`);
      btn.closest('.ficha-card').querySelector('.estado-text').innerText = nuevoEstado;
      Swal.fire({
        icon: 'success',
        title: `Ficha ${nuevoEstado === 'Activo' ? 'habilitada' : 'deshabilitada'} correctamente`,
        showConfirmButton: false,
        timer: 1500
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.error
      });
    }
  })
  .catch(error => {
    console.error('Error en la petición:', error);
  });
}

// Dropdown
function toggleDropdown() {
  document.getElementById("dropdownOptions").classList.toggle("show");
}

document.addEventListener("click", function (e) {
  const wrapper = document.querySelector(".dropdown-wrapper");
  if (!wrapper.contains(e.target)) {
    document.getElementById("dropdownOptions").classList.remove("show");
  }
});

// Buscador
const searchInput = document.getElementById("searchInput");
const fichaCards = document.querySelectorAll(".ficha-card");

searchInput.addEventListener("input", function () {
  const query = this.value.toLowerCase();
  fichaCards.forEach(card => {
    const numero = card.querySelector(".numero").textContent.toLowerCase();
    card.style.display = numero.includes(query) ? "block" : "none";
  });
});

// Filtro por jornada
document.querySelectorAll(".option").forEach(option => {
  option.addEventListener("click", function () {
    const jornadaSeleccionada = this.textContent.toLowerCase();
    fichaCards.forEach(card => {
      const jornadaFicha = card.getAttribute("data-jornada").toLowerCase();
      card.style.display = jornadaFicha.includes(jornadaSeleccionada) ? "block" : "none";
    });
    document.getElementById("dropdownOptions").classList.remove("show");
  });
});
