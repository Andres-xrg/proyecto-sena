document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("modoOscuroBtn");
  const body = document.body;

  const applyTheme = (isDark) => {
    body.classList.toggle("dark", isDark);
    localStorage.setItem("modoOscuro", isDark ? "1" : "0");
  };

  // Cargar preferencia guardada
  const modoGuardado = localStorage.getItem("modoOscuro");
  if (modoGuardado === "1") {
    applyTheme(true);
  }

  // Alternar al dar clic
  btn?.addEventListener("click", () => {
    const isDark = !body.classList.contains("dark");
    applyTheme(isDark);
  });
});
