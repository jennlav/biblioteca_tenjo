// =========================================================
// equipo.js
// Interacciones exclusivas de la vista Equipo
// Encabezado administrable + animaciones
// =========================================================

async function cargarEncabezadoEquipo() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=equipo", {
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
  cargarEncabezadoEquipo();

  const animatedItems = document.querySelectorAll(
    ".equipo-intro-box, .equipo-card, .equipo-cta-box"
  );

  animatedItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.animation = "equipoFadeUp 0.55s ease forwards";
    item.style.animationDelay = `${index * 0.055}s`;
  });
});

const equipoAnimationStyle = document.createElement("style");

equipoAnimationStyle.textContent = `
  @keyframes equipoFadeUp {
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
    .equipo-intro-box,
    .equipo-card,
    .equipo-cta-box {
      animation: none !important;
      opacity: 1 !important;
      transform: none !important;
    }
  }
`;

document.head.appendChild(equipoAnimationStyle);
