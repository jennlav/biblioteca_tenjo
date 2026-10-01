// =========================================================
// VISTA PROGRAMAS BIBLIOTECARIOS
// Encabezado administrable, interacción de tarjetas y modal
// con información institucional de cada programa.
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  cargarEncabezado();
  iniciarProgramas();
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
    const response = await fetch("../api/encabezados.php?vista=programas", {
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
// PROGRAMAS Y MODAL
// El modal muestra exclusivamente la información suministrada.
// =========================================================
function iniciarProgramas() {
  const cards = document.querySelectorAll(".programa-card");
  const actionButtons = document.querySelectorAll(".programa-card-action");

  const modal = document.getElementById("programaModal");
  const modalOverlay = document.getElementById("programaModalOverlay");
  const modalClose = document.getElementById("programaModalClose");
  const modalIcono = document.getElementById("programaModalIcono");
  const modalCategoria = document.getElementById("programaModalCategoria");
  const modalTitulo = document.getElementById("programaModalTitulo");
  const modalContenido = document.getElementById("programaModalContenido");

  let elementoActivoAntesDelModal = null;

  const programas = {
    rooted: {
      icono: '<i class="bi bi-translate"></i>',
      categoria: "Inglés y territorio",
      titulo: "Programa de Inglés Rooted in English",
      contenido: `
        <p>El Programa de Inglés Rooted in English de la Biblioteca Pública Municipal Isabel Murillo de Luque busca fortalecer las habilidades comunicativas en este idioma, brindando a la comunidad herramientas para su desarrollo académico, profesional y personal.</p>
        <p>A través de actividades de aprendizaje relacionadas con la cultura, el turismo y el patrimonio de Tenjo, el programa ofrece un espacio accesible y dinámico para niños, jóvenes y adultos.</p>
        <p><strong>¡Te invitamos a aprender, practicar y descubrir nuevas oportunidades a través del inglés!</strong></p>
        <div class="programa-modal-inscripciones">
          <h3>Informes e inscripciones</h3>
          <p><strong>Laura Rodriguez</strong></p>
          <p><i class="bi bi-telephone-fill" aria-hidden="true"></i> Celular de contacto: <a href="tel:+573014655926">301 4655926</a></p>
          <p><i class="bi bi-clock-fill" aria-hidden="true"></i> Horario de atención: lunes a jueves de 3:30 p.m. a 7:00 p.m.</p>
          <p><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> Escuela de Artes Vivas, Saberes y Oficios del Territorio de Tenjo - EAVISOT</p>
        </div>
      `
    },

    herbario: {
      icono: '<i class="bi bi-flower1"></i>',
      categoria: "Naturaleza y saber",
      titulo: "Programa Herbario del conocimiento",
      contenido: `
        <p>Herbario del Conocimiento es un programa de educación ambiental y cultural de la Biblioteca Pública Municipal Isabel Murillo de Luque, en Tenjo, que busca promover el conocimiento, la valoración y la conservación del patrimonio natural y cultural del municipio.</p>
        <p>A través de actividades pedagógicas, científicas, creativas y culturales, el programa articula los saberes tradicionales con el conocimiento científico, generando espacios de aprendizaje, participación y reflexión para la comunidad.</p>
        <p>Su propósito es fortalecer la conciencia ambiental, el sentido de pertenencia y el compromiso ciudadano con el territorio, consolidando a la biblioteca como un espacio de encuentro, aprendizaje y construcción de tejido social en torno al cuidado y la conservación del patrimonio natural de Tenjo.</p>
        <p><strong>¡Inscríbete a Herbario del Conocimiento!</strong></p>
        <p>Participa en este espacio de aprendizaje y conservación de nuestro patrimonio natural y cultural.</p>
      `
    },

    ubuntu: {
      icono: '<i class="bi bi-cpu-fill"></i>',
      categoria: "Ciencia y tecnología",
      titulo: "Programa de Robótica Mentes Ubuntu",
      contenido: `
        <p>El programa de Robótica Mentes Ubuntu es un espacio de aprendizaje e innovación que promueve el desarrollo del pensamiento lógico, la creatividad, la resolución de problemas y el trabajo en equipo a través de la robótica.</p>
        <p>El programa fortalece las habilidades digitales y tecnológicas de la comunidad, integrando ciencia, tecnología y valores como la cooperación, el respeto y la solidaridad, para contribuir a la formación de ciudadanos creativos, críticos y comprometidos con su entorno.</p>
        <p><strong>¡Te invitamos a participar y descubrir el mundo de la robótica!</strong></p>
        <div class="programa-modal-inscripciones">
          <h3>Informes e inscripciones</h3>
          <p><strong>Jhon ceydrec Quintero Castro</strong></p>
          <p><i class="bi bi-telephone-fill" aria-hidden="true"></i> Celular de contacto: <a href="tel:+573197029576">319 7029576</a></p>
          <p><i class="bi bi-clock-fill" aria-hidden="true"></i> Horario de clase: martes de 8:00 a.m. a 10:00 a.m. y jueves de 4:00 p.m. a 6:00 p.m.</p>
          <p><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> Sala STEAM del Centro Tecnológico, Tenjo</p>
        </div>
      `
    }
  };

  const abrirModal = (programaId) => {
    const programa = programas[programaId];

    if (!modal || !programa) return;

    elementoActivoAntesDelModal = document.activeElement;

    if (modalIcono) {
      modalIcono.innerHTML = programa.icono;
    }

    if (modalCategoria) {
      modalCategoria.textContent = programa.categoria;
    }

    if (modalTitulo) {
      modalTitulo.textContent = programa.titulo;
    }

    if (modalContenido) {
      modalContenido.innerHTML = programa.contenido;
    }

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

    if (elementoActivoAntesDelModal instanceof HTMLElement) {
      elementoActivoAntesDelModal.focus();
    }
  };

  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.animation = "programaCardIn 0.55s ease forwards";
    card.style.animationDelay = `${index * 0.10}s`;

    card.addEventListener("click", (event) => {
      if (event.target.closest(".programa-card-action")) return;
      abrirModal(card.dataset.programa);
    });

    card.addEventListener("keydown", (event) => {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        abrirModal(card.dataset.programa);
      }
    });
  });

  actionButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation();
      abrirModal(button.dataset.programa);
    });
  });

  modalClose?.addEventListener("click", cerrarModal);
  modalOverlay?.addEventListener("click", cerrarModal);

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal?.classList.contains("is-open")) {
      cerrarModal();
    }
  });
}
