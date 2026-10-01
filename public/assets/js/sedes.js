// =========================================================
// sedes.js
// Interacciones exclusivas de la vista Sedes
// Encabezado administrable + animaciones
// =========================================================

async function cargarEncabezadoSedes() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=sedes", {
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
  cargarEncabezadoSedes();

  const items = document.querySelectorAll(
    ".sedes-intro-box, .sede-card, .sedes-highlight-box"
  );

  items.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.animation = "fadeUpSedes 0.55s ease forwards";
    item.style.animationDelay = `${index * 0.08}s`;
  });
});

const sedesAnimationStyle = document.createElement("style");

sedesAnimationStyle.textContent = `
  @keyframes fadeUpSedes {
    from {
      opacity: 0;
      transform: translateY(18px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
`;

document.head.appendChild(sedesAnimationStyle);
