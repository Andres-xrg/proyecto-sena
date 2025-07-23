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

// ------------------------ Dropdowns ------------------------

function toggleDropdown() {
  document.getElementById("dropdownOptions").classList.toggle("show");
}
function toggleDropdownTipoOferta() {
  document.getElementById('dropdownTipoOfertaOptions').classList.toggle('show');
}
function toggleDropdownEstado() {
  document.getElementById('dropdownEstadoOptions').classList.toggle('show');
}

// ------------------------ Selectores ------------------------

function seleccionarJornada(jornada) {
  const span = document.getElementById('selectedJornada');
  document.getElementById('jornadaHidden').value = jornada === 'Todos' ? '' : jornada;
  span.textContent = jornada === 'Todos' ? 'Seleccionar jornada...' : jornada;
  document.querySelector('.controls form').submit();
}

function seleccionarTipoOferta(oferta) {
  const span = document.getElementById('selectedTipoOferta');
  document.getElementById('tipoOfertaHidden').value = oferta === 'Todos' ? '' : oferta;
  span.textContent = oferta === 'Todos' ? 'Tipo de oferta' : oferta;
  document.querySelector('.controls form').submit();
}

function seleccionarEstado(estado) {
  const span = document.getElementById('selectedEstado');
  document.getElementById('estadoHidden').value = estado === 'Todos' ? '' : estado.toLowerCase();
  span.textContent = estado === 'Todos' ? 'Filtrar por estado' : estado;
  document.querySelector('.controls form').submit();
}

// ------------------------ Ocultar menú al hacer clic fuera ------------------------

document.addEventListener("click", function (e) {
  const wrappers = document.querySelectorAll(".dropdown-wrapper");
  wrappers.forEach(wrapper => {
    if (!wrapper.contains(e.target)) {
      wrapper.querySelectorAll(".dropdown-options").forEach(optionBox => optionBox.classList.remove("show"));
    }
  });
});

// ------------------------ Buscador ------------------------

const searchInput = document.getElementById("searchInput");
const fichaCards = document.querySelectorAll(".ficha-card");

searchInput.addEventListener("input", function () {
  const query = this.value.toLowerCase();
  fichaCards.forEach(card => {
    const numero = card.querySelector(".numero").textContent.toLowerCase();
    card.style.display = numero.includes(query) ? "block" : "none";
  });
});
