// =========================================================
// VISTA ACTIVIDADES TRANSVERSALES
// Encabezado administrable, animación de tarjetas y modal
// de reproducción de podcast.
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  cargarEncabezado();
  iniciarAnimacionTarjetas();
  iniciarModalPodcast();
});

// =========================================================
// ENCABEZADO ADMINISTRABLE
// Conserva el contenido estático si el API no está disponible.
// =========================================================
async function cargarEncabezado() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=actividades-transversales", {
      method: "GET",
      headers: {
        Accept: "application/json"
      },
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
    // Se mantiene el contenido estático definido en el HTML.
  }
}

// =========================================================
// ANIMACIÓN DE TARJETAS
// Aplica una entrada progresiva respetando el orden visual.
// =========================================================
function iniciarAnimacionTarjetas() {
  const cards = document.querySelectorAll(".actividad-card");

  cards.forEach((card, index) => {
    card.style.animation = "fadeUpCard 0.55s ease forwards";
    card.style.animationDelay = `${index * 0.08}s`;
    card.style.opacity = "0";
  });
}

// =========================================================
// MODAL DE PODCAST
// Gestiona apertura, cierre, teclado, foco y reproducción.
// =========================================================
function iniciarModalPodcast() {
  const abrirModal = document.getElementById("abrirPodcastModal");
  const modal = document.getElementById("podcastModal");
  const cerrarModal = document.getElementById("podcastModalClose");
  const overlay = document.getElementById("podcastModalOverlay");

  if (!abrirModal || !modal) return;

  let elementoConFoco = null;

  const abrir = () => {
    elementoConFoco = document.activeElement;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";

    if (cerrarModal) {
      cerrarModal.focus();
    }
  };

  const cerrar = () => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";

    modal.querySelectorAll("audio").forEach((audio) => {
      audio.pause();
    });

    if (elementoConFoco instanceof HTMLElement) {
      elementoConFoco.focus();
    }
  };

  abrirModal.addEventListener("click", abrir);

  if (cerrarModal) {
    cerrarModal.addEventListener("click", cerrar);
  }

  if (overlay) {
    overlay.addEventListener("click", cerrar);
  }

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal.classList.contains("is-open")) {
      cerrar();
    }
  });
}
