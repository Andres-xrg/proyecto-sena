        // Activar modo oscuro al hacer clic
        const modoBtn = document.getElementById("modoOscuroBtn");
        const icon = modoBtn.querySelector("i");

        modoBtn.addEventListener("click", () => {
            document.body.classList.toggle("dark");

            // Cambiar el ícono
            if (document.body.classList.contains("dark")) {
                icon.classList.remove("fa-moon");
                icon.classList.add("fa-sun");
            } else {
                icon.classList.remove("fa-sun");
                icon.classList.add("fa-moon");
            }
        });