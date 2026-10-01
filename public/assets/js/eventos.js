// =========================================================
// VISTA EVENTOS
// Catálogo dinámico, filtros, modal y encabezado administrable.
// Fuente de datos principal: API PHP /api/eventos.php.
// =========================================================

// =========================================================
// ENCABEZADO ADMINISTRABLE
// Conserva el contenido estático si el API no está disponible.
// =========================================================
async function cargarEncabezadoEventos() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=eventos", {
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

document.addEventListener("DOMContentLoaded", async () => {
  cargarEncabezadoEventos();

  const API_URL = "../api/eventos.php";

  const grid = document.getElementById("eventosGrid");
  const inputBusqueda = document.getElementById("eventoBusqueda");
  const selectCategoria = document.getElementById("eventoCategoria");
  const fechaDesde = document.getElementById("eventoFechaDesde");
  const fechaHasta = document.getElementById("eventoFechaHasta");
  const btnBuscar = document.getElementById("eventoBuscar");
  const btnLimpiar = document.getElementById("eventoLimpiar");
  const btnLimpiarEmpty = document.getElementById("eventoLimpiarEmpty");
  const contador = document.getElementById("eventosContador");
  const emptyState = document.getElementById("eventosEmpty");

  const modal = document.getElementById("eventoModal");
  const modalImagen = document.getElementById("eventoModalImagen");
  const modalCategoria = document.getElementById("eventoModalCategoria");
  const modalFecha = document.getElementById("eventoModalFecha");
  const modalTitulo = document.getElementById("eventoModalTitulo");
  const modalDescripcion = document.getElementById("eventoModalDescripcion");
  const modalCloseElements = document.querySelectorAll("[data-evento-close]");

  let eventos = [];
  let lastFocusedElement = null;

  const normalize = (text) =>
    String(text ?? "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();

  const escapeHtml = (value) =>
    String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

  const createExcerpt = (text, maxLength = 132) => {
    const clean = String(text ?? "").replace(/\s+/g, " ").trim();

    if (!clean) return "";

    if (clean.length <= maxLength) {
      return `${clean}...`;
    }

    const cut = clean.slice(0, maxLength);
    const lastSpace = cut.lastIndexOf(" ");

    return `${cut
      .slice(0, lastSpace > 85 ? lastSpace : maxLength)
      .trim()}...`;
  };

  const parseDate = (dateString) => {
    if (!dateString) return null;

    const parts = dateString.split("-").map(Number);

    if (parts.length !== 3 || parts.some(Number.isNaN)) {
      return null;
    }

    return {
      year: parts[0],
      month: parts[1],
      day: parts[2]
    };
  };

  const monthName = (month) => {
    const meses = [
      "enero",
      "febrero",
      "marzo",
      "abril",
      "mayo",
      "junio",
      "julio",
      "agosto",
      "septiembre",
      "octubre",
      "noviembre",
      "diciembre"
    ];

    return meses[month - 1] ?? "";
  };

  const lastDayOfMonth = (year, month) =>
    new Date(year, month, 0).getDate();

  const formatDateText = (start, end) => {
    const startDate = parseDate(start);
    const endDate = parseDate(end || start);

    if (!startDate) return "";

    if (!endDate) {
      return `${startDate.day} de ${monthName(startDate.month)}`;
    }

    if (
      startDate.year === endDate.year &&
      startDate.month === endDate.month &&
      startDate.day === endDate.day
    ) {
      return `${startDate.day} de ${monthName(startDate.month)}`;
    }

    if (
      startDate.year === endDate.year &&
      startDate.month === endDate.month &&
      startDate.day === 1 &&
      endDate.day === lastDayOfMonth(endDate.year, endDate.month)
    ) {
      return monthName(startDate.month);
    }

    if (
      startDate.year === endDate.year &&
      startDate.month === endDate.month
    ) {
      return `${startDate.day} al ${endDate.day} de ${monthName(
        startDate.month
      )}`;
    }

    return `${startDate.day} de ${monthName(
      startDate.month
    )} al ${endDate.day} de ${monthName(endDate.month)}`;
  };

  const mapEvento = (evento) => ({
    id: Number(evento.id),
    title: evento.titulo ?? "",
    description: evento.descripcion ?? "",
    dateStart: evento.fecha_inicio ?? "",
    dateEnd: evento.fecha_fin || evento.fecha_inicio || "",
    dateText: formatDateText(
      evento.fecha_inicio,
      evento.fecha_fin || evento.fecha_inicio
    ),
    category: evento.categoria ?? "",
    image: evento.imagen_url
      ? evento.imagen_url
      : evento.imagen
        ? `../${evento.imagen}`
        : ""
  });

  const syncCategories = () => {
    if (!selectCategoria) return;

    const categories = [
      ...new Set(
        eventos
          .map((evento) => evento.category)
          .filter(Boolean)
      )
    ].sort((a, b) => a.localeCompare(b, "es"));

    selectCategoria.innerHTML = `
      <option value="">- Cualquiera -</option>
      ${categories
        .map(
          (category) =>
            `<option value="${escapeHtml(category)}">${escapeHtml(
              category
            )}</option>`
        )
        .join("")}
    `;
  };

  const renderEventos = (items) => {
    if (!grid) return;

    grid.innerHTML = items
      .map(
        (evento) => `
          <article
            class="evento-card"
            data-event-id="${evento.id}"
            data-category="${escapeHtml(evento.category)}"
            data-date-start="${escapeHtml(evento.dateStart)}"
            data-date-end="${escapeHtml(evento.dateEnd)}"
          >
            <div class="evento-card-date-row">
              <span class="evento-card-date">
                ${escapeHtml(evento.dateText)}
              </span>
            </div>

            <div class="evento-card-media">
              ${
                evento.image
                  ? `
                    <img
                      src="${escapeHtml(evento.image)}"
                      alt="${escapeHtml(evento.title)}"
                      loading="lazy"
                    >
                  `
                  : `
                    <div class="evento-card-no-image" aria-hidden="true">
                      <i class="bi bi-calendar-event"></i>
                    </div>
                  `
              }
            </div>

            <div class="evento-card-body">
              <span
                class="evento-card-category"
                title="${escapeHtml(evento.category)}"
              >
                ${escapeHtml(evento.category)}
              </span>

              <h3>${escapeHtml(evento.title)}</h3>

              <p class="evento-card-resumen">
                ${escapeHtml(createExcerpt(evento.description))}
              </p>

              <button
                type="button"
                class="evento-card-action"
                data-open-event="${evento.id}"
                aria-label="Conocer más sobre ${escapeHtml(evento.title)}"
              >
                Conoce más
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
              </button>
            </div>
          </article>
        `
      )
      .join("");

    grid.querySelectorAll(".evento-card").forEach((card, index) => {
      card.style.opacity = "0";
      card.style.animation = "fadeUpEvento 0.55s ease forwards";
      card.style.animationDelay = `${Math.min(index, 9) * 0.045}s`;
    });

    grid.querySelectorAll("[data-open-event]").forEach((button) => {
      button.addEventListener("click", () => {
        const id = Number(button.dataset.openEvent);
        const evento = eventos.find((item) => item.id === id);

        if (evento) {
          openModal(evento, button);
        }
      });
    });

    if (contador) {
      contador.textContent =
        items.length === 1
          ? "1 evento encontrado"
          : `${items.length} eventos encontrados`;
    }

    if (emptyState) {
      emptyState.hidden = items.length > 0;
    }
  };

  const getFilteredEventos = () => {
    const term = normalize(inputBusqueda?.value);
    const category = selectCategoria?.value ?? "";
    const from = fechaDesde?.value ?? "";
    const to = fechaHasta?.value ?? "";

    return eventos.filter((evento) => {
      const searchable = normalize(
        `${evento.title} ${evento.description} ${evento.category} ${evento.dateText}`
      );

      const matchesText = !term || searchable.includes(term);
      const matchesCategory =
        !category || evento.category === category;
      const matchesFrom = !from || evento.dateEnd >= from;
      const matchesTo = !to || evento.dateStart <= to;

      return (
        matchesText &&
        matchesCategory &&
        matchesFrom &&
        matchesTo
      );
    });
  };

  const applyFilters = () => {
    renderEventos(getFilteredEventos());
  };

  const clearFilters = () => {
    if (inputBusqueda) inputBusqueda.value = "";
    if (selectCategoria) selectCategoria.value = "";
    if (fechaDesde) fechaDesde.value = "";
    if (fechaHasta) fechaHasta.value = "";

    renderEventos(eventos);
  };

  const openModal = (evento, trigger) => {
    if (!modal) return;

    lastFocusedElement = trigger;

    if (modalImagen) {
      if (evento.image) {
        modalImagen.src = evento.image;
        modalImagen.alt = evento.title;
        modalImagen.style.display = "";
      } else {
        modalImagen.src = "";
        modalImagen.alt = "";
        modalImagen.style.display = "none";
      }
    }

    if (modalCategoria) {
      modalCategoria.textContent = evento.category;
    }

    if (modalFecha) {
      modalFecha.textContent = evento.dateText;
    }

    if (modalTitulo) {
      modalTitulo.textContent = evento.title;
    }

    if (modalDescripcion) {
      modalDescripcion.textContent = evento.description;
    }

    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("evento-modal-open");

    modal.querySelector(".evento-modal-close")?.focus();
  };

  const closeModal = () => {
    if (!modal) return;

    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("evento-modal-open");

    if (modalImagen) {
      modalImagen.src = "";
      modalImagen.alt = "";
      modalImagen.style.display = "";
    }

    lastFocusedElement?.focus();
  };

  btnBuscar?.addEventListener("click", applyFilters);
  btnLimpiar?.addEventListener("click", clearFilters);
  btnLimpiarEmpty?.addEventListener("click", clearFilters);

  inputBusqueda?.addEventListener("input", applyFilters);
  selectCategoria?.addEventListener("change", applyFilters);
  fechaDesde?.addEventListener("change", applyFilters);
  fechaHasta?.addEventListener("change", applyFilters);

  inputBusqueda?.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      applyFilters();
    }
  });

  modalCloseElements.forEach((element) => {
    element.addEventListener("click", closeModal);
  });

  document.addEventListener("keydown", (event) => {
    if (
      event.key === "Escape" &&
      modal?.classList.contains("is-open")
    ) {
      closeModal();
    }
  });

  try {
    if (contador) {
      contador.textContent = "Cargando eventos...";
    }

    const response = await fetch(API_URL, {
      method: "GET",
      headers: {
        Accept: "application/json"
      },
      cache: "no-store"
    });

    if (!response.ok) {
      throw new Error(
        `La API respondió con estado ${response.status}`
      );
    }

    const data = await response.json();

    if (!Array.isArray(data)) {
      throw new Error(
        "La respuesta de la API no tiene el formato esperado."
      );
    }

    eventos = data.map(mapEvento);

    syncCategories();
    renderEventos(eventos);
  } catch (error) {
    console.error("No fue posible cargar los eventos:", error);

    if (grid) {
      grid.innerHTML = "";
    }

    if (contador) {
      contador.textContent =
        "No fue posible cargar los eventos.";
    }

    if (emptyState) {
      emptyState.hidden = false;

      const title = emptyState.querySelector("h3");
      const text = emptyState.querySelector("p");

      if (title) {
        title.textContent =
          "No fue posible cargar los eventos";
      }

      if (text) {
        text.textContent =
          "Verifica que el backend local esté disponible e intenta nuevamente.";
      }
    }
  }
});