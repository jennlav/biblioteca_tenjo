// =========================================================
// VISTA SERVICIOS
// Encabezado administrable, filtrado del catálogo y modal
// accesible de detalle.

// =========================================================
document.addEventListener("DOMContentLoaded", () => {
  const API_ENCABEZADO_URL = "../api/encabezados.php?vista=servicios";
  const formBusqueda = document.querySelector(".search-servicios");
  const inputBusqueda = document.querySelector(".search-servicios input");
  const gridServicios = document.querySelector(".servicios-grid");
  const tarjetas = document.querySelectorAll(".servicio-card");
  const modal = document.getElementById("modalServicio");
  const modalOverlay = document.getElementById("modalServicioOverlay");
  const modalClose = document.getElementById("modalServicioClose");
  const modalTitulo = document.getElementById("modalServicioTitulo");
  const modalResumen = document.getElementById("modalServicioResumen");
  const modalDetalle = document.getElementById("modalServicioDetalle");
  const modalHorario = document.getElementById("modalServicioHorario");
  let elementoActivoAntesDelModal = null;

  // =========================================================
  // ENCABEZADO ADMINISTRABLE
  // Consume la configuración pública de la vista desde el API.

  // =========================================================
  const cargarEncabezado = async () => {
    const etiqueta = document.getElementById("encabezado-etiqueta");
    const titulo = document.getElementById("encabezado-titulo");
    const descripcion = document.getElementById("encabezado-descripcion");
    if (!etiqueta && !titulo && !descripcion) return;
    try {
      const response = await fetch(API_ENCABEZADO_URL, { method: "GET", headers: { Accept: "application/json" }, cache: "no-store" });
      if (!response.ok) return;
      const resultado = await response.json();
      if (!resultado?.success || !resultado.data) return;
      const encabezado = resultado.data;
      if (etiqueta && encabezado.etiqueta !== null) etiqueta.textContent = encabezado.etiqueta;
      if (titulo && encabezado.titulo) titulo.textContent = encabezado.titulo;
      if (descripcion && encabezado.descripcion !== null) descripcion.textContent = encabezado.descripcion;
    } catch (error) {
      // Conserva el contenido estático del HTML como respaldo.
    }
  };

  // =========================================================
  // FILTRADO DEL CATÁLOGO
  // Realiza búsqueda local sobre el contenido visible.

  // =========================================================
  if (formBusqueda && inputBusqueda && tarjetas.length) {
    const filtrarTarjetas = () => {
      const termino = inputBusqueda.value.trim().toLowerCase();
      tarjetas.forEach((tarjeta) => {
        const texto = `${tarjeta.innerText} ${tarjeta.dataset.detalle || ""}`.toLowerCase();
        tarjeta.hidden = termino !== "" && !texto.includes(termino);
      });
    };
    formBusqueda.addEventListener("submit", (event) => { event.preventDefault(); filtrarTarjetas(); });
    inputBusqueda.addEventListener("input", filtrarTarjetas);
  }

  // =========================================================
  // MODAL DE DETALLE
  // Carga resumen, información ampliada y horario del servicio.

  // =========================================================
  const abrirModal = (tarjeta) => {
    if (!modal || !tarjeta) return;
    elementoActivoAntesDelModal = document.activeElement;
    if (modalTitulo) modalTitulo.textContent = tarjeta.dataset.titulo || "";
    if (modalResumen) modalResumen.textContent = tarjeta.dataset.descripcion || "";
    if (modalDetalle) modalDetalle.textContent = tarjeta.dataset.detalle || "";
    if (modalHorario) modalHorario.textContent = tarjeta.dataset.horario || "";
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    modalClose?.focus();
  };
  const cerrarModal = () => {
    if (!modal) return;
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    if (elementoActivoAntesDelModal instanceof HTMLElement) elementoActivoAntesDelModal.focus();
  };
  gridServicios?.addEventListener("click", (event) => {
    const boton = event.target.closest(".servicio-card-link");
    if (!boton) return;
    abrirModal(boton.closest(".servicio-card"));
  });
  modalClose?.addEventListener("click", cerrarModal);
  modalOverlay?.addEventListener("click", cerrarModal);
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal?.classList.contains("is-open")) cerrarModal();
  });
  cargarEncabezado();
});
