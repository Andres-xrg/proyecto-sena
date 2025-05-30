document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const btnModoOscuro = document.getElementById("modoOscuroBtn");

    // Aplica el modo oscuro si ya está activado en localStorage
    if (localStorage.getItem("modoOscuro") === "true") {
        body.classList.add("dark");
    }

    // Evento del botón
    btnModoOscuro.addEventListener("click", () => {
        body.classList.toggle("dark");
        const modoActivo = body.classList.contains("dark");
        localStorage.setItem("modoOscuro", modoActivo);
    });
});