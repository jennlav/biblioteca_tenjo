// =========================================================
// documentacion.js
// Vista Documentación Pública
// Fuente de datos: API PHP /api/documentos.php
// =========================================================

async function cargarEncabezadoDocumentacion() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=documentacion", {
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

document.addEventListener("DOMContentLoaded", async () => {
  cargarEncabezadoDocumentacion();

  const API_URL = "../api/documentos.php";

  const searchForm = document.getElementById("documentacionSearchForm");
  const searchInput = document.getElementById("documentacionSearchInput");
  const grid = document.getElementById("documentacionGrid");
  const emptyState = document.getElementById("documentacionEmpty");

  let documentos = [];

  const escapeHtml = (value) =>
    String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

  const normalizarTexto = (texto) =>
    String(texto ?? "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();

  const formatearFecha = (fecha) => {
    if (!fecha) return "";

    const [year, month, day] = fecha.split("-");

    if (!year || !month || !day) {
      return fecha;
    }

    return `${day}/${month}/${year}`;
  };

  const formatearTamano = (bytes) => {
    const value = Number(bytes);

    if (!Number.isFinite(value) || value <= 0) {
      return "";
    }

    if (value >= 1024 * 1024) {
      return `${(value / (1024 * 1024)).toFixed(1)} MB`;
    }

    return `${(value / 1024).toFixed(1)} KB`;
  };

  const obtenerIcono = (tipo) => {
    const t = String(tipo ?? "").toUpperCase();

    if (t === "PDF") return "bi-file-earmark-pdf";
    if (["DOC", "DOCX"].includes(t)) return "bi-file-earmark-word";
    if (["XLS", "XLSX"].includes(t)) return "bi-file-earmark-excel";

    return "bi-file-earmark-text";
  };

  const renderDocumentos = (items) => {
    if (!grid) return;

    grid.innerHTML = items
      .map((documento) => {
        const fecha = formatearFecha(documento.fecha_documento);
        const tamano = formatearTamano(documento.tamano_archivo);
        const tipo = documento.tipo_archivo || "Documento";

        const detalles = [
          fecha ? `Fecha: ${fecha}` : "",
          tamano ? `Tamaño: ${tamano}` : ""
        ]
          .filter(Boolean)
          .join(" · ");

        return `
          <article class="featured-item documento-publico-item">
            <div class="documento-publico-info">
              <span class="featured-item-icon" aria-hidden="true">
                <i class="bi ${obtenerIcono(tipo)}"></i>
              </span>

              <div class="documento-publico-copy">
                <span class="documento-publico-tipo">
                  ${escapeHtml(tipo)}
                </span>

                <strong>
                  ${escapeHtml(documento.titulo)}
                </strong>

                ${
                  documento.descripcion
                    ? `<small>${escapeHtml(documento.descripcion)}</small>`
                    : ""
                }

                ${
                  detalles
                    ? `<small>${escapeHtml(detalles)}</small>`
                    : ""
                }
              </div>
            </div>

            <a
              href="${escapeHtml(documento.archivo_url ?? "#")}"
              class="documento-publico-link"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Consultar ${escapeHtml(documento.titulo)}"
            >
              Ver documento
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
          </article>
        `;
      })
      .join("");

    grid
      .querySelectorAll(".documento-publico-item")
      .forEach((element, index) => {
        element.style.opacity = "0";
        element.style.animation =
          "documentacionFadeUp 0.55s ease forwards";
        element.style.animationDelay =
          `${Math.min(index, 8) * 0.07}s`;
      });

    if (emptyState) {
      emptyState.hidden = items.length > 0;
    }
  };

  const filtrarDocumentos = () => {
    const termino = normalizarTexto(searchInput?.value);

    if (!termino) {
      renderDocumentos(documentos);
      return;
    }

    const filtrados = documentos.filter((documento) => {
      const contenido = normalizarTexto(
        `${documento.titulo}
         ${documento.descripcion ?? ""}
         ${documento.tipo_archivo ?? ""}
         ${documento.nombre_archivo_original ?? ""}
         ${documento.fecha_documento ?? ""}`
      );

      return contenido.includes(termino);
    });

    renderDocumentos(filtrados);
  };

  searchInput?.addEventListener("input", filtrarDocumentos);

  searchForm?.addEventListener("submit", (event) => {
    event.preventDefault();
    filtrarDocumentos();
  });

  try {
    if (grid) {
      grid.innerHTML = `
        <div class="documentacion-loading">
          Cargando documentos...
        </div>
      `;
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

    documentos = data;
    renderDocumentos(documentos);

  } catch (error) {
    console.error(
      "No fue posible cargar los documentos:",
      error
    );

    if (grid) {
      grid.innerHTML = "";
    }

    if (emptyState) {
      emptyState.hidden = false;

      const titulo = emptyState.querySelector("h3");
      const texto = emptyState.querySelector("p");

      if (titulo) {
        titulo.textContent =
          "No fue posible cargar los documentos";
      }

      if (texto) {
        texto.textContent =
          "Verifica que el backend local esté disponible e intenta nuevamente.";
      }
    }
  }
});

const documentacionAnimationStyle =
  document.createElement("style");

documentacionAnimationStyle.textContent = `
  @keyframes documentacionFadeUp {
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
    .documento-publico-item {
      animation: none !important;
      opacity: 1 !important;
      transform: none !important;
    }
  }
`;

document.head.appendChild(documentacionAnimationStyle);
