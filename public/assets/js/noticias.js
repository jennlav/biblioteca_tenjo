// =========================================================
// noticias.js
// Interacciones exclusivas de la vista Noticias
// Encabezado administrable + animaciones
// =========================================================

async function cargarEncabezadoNoticias() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=noticias", {
      method: "GET",
      headers: { Accept: "application/json" },
      cache: "no-store"
    });

    if (!response.ok) return;

    const resultado = await response.json();

    if (!resultado.success || !resultado.data) return;

    const encabezado = resultado.data;

    if (etiqueta && encabezado.etiqueta !== null) {
      etiqueta.textContent = encabezado.etiqueta;
    }

    if (titulo && encabezado.titulo) {
      titulo.textContent = encabezado.titulo;
    }

    if (descripcion && encabezado.descripcion !== null) {
      descripcion.textContent = encabezado.descripcion;
    }
  } catch (error) {
    // Si la API no responde, se conserva el contenido estático del HTML.
  }
}

document.addEventListener("DOMContentLoaded", () => {
  cargarEncabezadoNoticias();

  const animatedItems = document.querySelectorAll(
    ".noticia-card, .stat-item"
  );

  animatedItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.animation = "fadeUpNoticia 0.6s ease forwards";
    item.style.animationDelay = `${index * 0.05}s`;
  });
});

const noticiasAnimationStyle = document.createElement("style");

noticiasAnimationStyle.textContent = `
  @keyframes fadeUpNoticia {
    from {
      opacity: 0;
      transform: translateY(18px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .noticia-card,
    .stat-item {
      animation: none !important;
      opacity: 1 !important;
      transform: none !important;
    }
  }
`;

document.head.appendChild(noticiasAnimationStyle);
