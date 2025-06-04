function toggleDropdown() {
    const options = document.getElementById('dropdownOptions');

    // Alternar visibilidad
    if (options.style.display === 'block') {
        options.style.display = 'none';
        localStorage.setItem('dropdownVisible', 'false');
    } else {
        options.style.display = 'block';
        localStorage.setItem('dropdownVisible', 'true');
    }
}

window.onload = function() {
    const options = document.getElementById('dropdownOptions');
    const visible = localStorage.getItem('dropdownVisible');

    if (visible === 'true') {
        options.style.display = 'block';
    } else {
        options.style.display = 'none';
    }
}

function verFicha() {
            window.location.href = 'index.php?page=components/Fichas/Ficha_vista';
        }    

 function toggleFicha(button) {
        const fichaCard = button.closest(".ficha-card");

        if (fichaCard.classList.contains("disabled")) {
            fichaCard.classList.remove("disabled");
            button.textContent = "Deshabilitar";
            button.classList.remove("btn-habilitar");
            button.classList.add("btn-deshabilitar");

            const btnVer = fichaCard.querySelector(".btn-ver-ficha");
            btnVer.classList.remove("disabled");
            btnVer.disabled = false;
        } else {
            fichaCard.classList.add("disabled");
            button.textContent = "Habilitar";
            button.classList.remove("btn-deshabilitar");
            button.classList.add("btn-habilitar");

            const btnVer = fichaCard.querySelector(".btn-ver-ficha");
            btnVer.classList.add("disabled");
            btnVer.disabled = true;
        }
    }

 