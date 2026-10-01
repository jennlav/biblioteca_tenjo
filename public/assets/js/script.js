// =========================================================
// INTERACCIONES GLOBALES DEL FRONTEND
// Banners, navegación responsive, recomendaciones y eventos del Home.
// =========================================================



// =========================================================
// BANNER PRINCIPAL ADMINISTRABLE
// Consume ../api/banners.php y construye el carrusel Bootstrap.
// =========================================================

document.addEventListener("DOMContentLoaded", async () => {

  const heroElement = document.getElementById("heroCarousel");

  const heroInner = document.getElementById("heroCarouselInner");



  if (!heroElement || !heroInner) return;



  const API_BANNERS_URL = "../api/banners.php";



  const escapeHtml = (value) =>

    String(value ?? "")

      .replace(/&/g, "&amp;")

      .replace(/</g, "&lt;")

      .replace(/>/g, "&gt;")

      .replace(/"/g, "&quot;")

      .replace(/'/g, "&#039;");



  const renderButton = (text, url, variant) => {

    if (!text || !url) return "";



    return `

      <a

        href="${escapeHtml(url)}"

        class="btn ${variant}"

      >

        ${escapeHtml(text)}

      </a>

    `;

  };



  try {

    const response = await fetch(API_BANNERS_URL, {

      method: "GET",

      headers: {

        Accept: "application/json"

      },

      cache: "no-store"

    });



    if (!response.ok) {

      throw new Error(

        `La API de banners respondió con estado ${response.status}`

      );

    }



    const banners = await response.json();



    if (!Array.isArray(banners)) {

      throw new Error(

        "La respuesta de la API de banners no tiene el formato esperado."

      );

    }



    if (banners.length === 0) {

      heroInner.innerHTML = `

        <div class="carousel-item active">

          <div class="hero-slide">

            <div class="overlay-gradient"></div>

            <div class="container hero-content">

              <h1>Biblioteca Pública Municipal Isabel Murillo de Luque</h1>

              <p>Actualmente no hay banners activos para mostrar.</p>

            </div>

          </div>

        </div>

      `;

      return;

    }



    heroInner.innerHTML = banners

      .map((banner, index) => {

        const button1 = renderButton(

          banner.boton1_texto,

          banner.boton1_url,

          "btn-main"

        );



        const button2 = renderButton(

          banner.boton2_texto,

          banner.boton2_url,

          "btn-light-custom"

        );



        const buttons =

          button1 || button2

            ? `<div class="buttons">${button1}${button2}</div>`

            : "";



        const backgroundImage = banner.imagen_url

          ? `style="background-image:url('${banner.imagen_url}')"`

          : "";



        return `

          <div class="carousel-item ${index === 0 ? "active" : ""}">

            <div class="hero-slide" ${backgroundImage}>

              <div class="overlay-gradient"></div>



              <div class="container hero-content">

                <h1>${escapeHtml(banner.titulo)}</h1>

                <p>${escapeHtml(banner.descripcion)}</p>

                ${buttons}

              </div>

            </div>

          </div>

        `;

      })

      .join("");



    if (

      banners.length > 1 &&

      typeof bootstrap !== "undefined"

    ) {

      new bootstrap.Carousel(heroElement, {

        interval: 6000,

        ride: "carousel",

        pause: "hover"

      });

    }



  } catch (error) {

    console.error("No fue posible cargar los banners:", error);



    heroInner.innerHTML = `

      <div class="carousel-item active">

        <div class="hero-slide">

          <div class="overlay-gradient"></div>

          <div class="container hero-content">

            <h1>Biblioteca Pública Municipal Isabel Murillo de Luque</h1>

            <p>No fue posible cargar el banner principal.</p>

          </div>

        </div>

      </div>

    `;

  }

});



// Navegación horizontal asistida para recomendaciones literarias

document.addEventListener("DOMContentLoaded", () => {

  const booksContainer = document.querySelector(".books");



  if (booksContainer) {

    booksContainer.addEventListener(

      "wheel",

      (event) => {

        if (Math.abs(event.deltaY) <= Math.abs(event.deltaX)) return;



        event.preventDefault();

        booksContainer.scrollLeft += event.deltaY;

      },

      { passive: false }

    );

  }

});



// Navegación responsive y control accesible de submenús

document.addEventListener("DOMContentLoaded", () => {

  const toggle = document.getElementById("menu-toggle");

  const menu = document.getElementById("nav-menu");

  const dropdowns = document.querySelectorAll(".nav-item-dropdown");

  const mobileBreakpoint = 992;



  const isMobileView = () => window.innerWidth <= mobileBreakpoint;



  const closeAllDropdowns = () => {

    dropdowns.forEach((dropdown) => {

      dropdown.classList.remove("is-open");



      const button = dropdown.querySelector(".nav-dropdown-toggle");

      if (button) {

        button.setAttribute("aria-expanded", "false");

      }

    });

  };



  if (toggle && menu) {

    toggle.addEventListener("click", () => {

      const menuIsOpen = menu.classList.toggle("active");

      toggle.setAttribute("aria-expanded", String(menuIsOpen));



      if (!menuIsOpen) {

        closeAllDropdowns();

      }

    });



    toggle.addEventListener("keydown", (event) => {

      if (event.key === "Enter" || event.key === " ") {

        event.preventDefault();

        toggle.click();

      }

    });

  }



  dropdowns.forEach((dropdown) => {

    const button = dropdown.querySelector(".nav-dropdown-toggle");



    if (!button) return;



    button.addEventListener("click", (event) => {

      if (!isMobileView()) return;



      event.preventDefault();



      const isOpen = dropdown.classList.contains("is-open");

      closeAllDropdowns();



      if (!isOpen) {

        dropdown.classList.add("is-open");

        button.setAttribute("aria-expanded", "true");

      }

    });



    button.addEventListener("keydown", (event) => {

      if (!isMobileView() && (event.key === "Enter" || event.key === " ")) {

        event.preventDefault();



        const isOpen = dropdown.classList.contains("is-open");

        closeAllDropdowns();



        if (!isOpen) {

          dropdown.classList.add("is-open");

          button.setAttribute("aria-expanded", "true");

        }

      }



      if (event.key === "Escape") {

        dropdown.classList.remove("is-open");

        button.setAttribute("aria-expanded", "false");

        button.focus();

      }

    });



    dropdown.addEventListener("mouseenter", () => {

      if (!isMobileView()) {

        dropdown.classList.add("is-open");

        button.setAttribute("aria-expanded", "true");

      }

    });



    dropdown.addEventListener("mouseleave", () => {

      if (!isMobileView()) {

        dropdown.classList.remove("is-open");

        button.setAttribute("aria-expanded", "false");

      }

    });

  });



  document.addEventListener("click", (event) => {

    const clickedInsideMenu = event.target.closest(".nav-wrapper");



    if (!clickedInsideMenu) {

      if (menu) {

        menu.classList.remove("active");

      }



      if (toggle) {

        toggle.setAttribute("aria-expanded", "false");

      }



      closeAllDropdowns();

    }

  });



  window.addEventListener("resize", () => {

    if (!isMobileView()) {

      if (menu) {

        menu.classList.remove("active");

      }



      if (toggle) {

        toggle.setAttribute("aria-expanded", "false");

      }



      closeAllDropdowns();

    }

  });

});





// =========================================================
// EVENTOS DESTACADOS DEL HOME
// Consume la agenda pública y renderiza únicamente los tres
// próximos eventos activos ordenados por fecha de inicio.
// =========================================================
document.addEventListener("DOMContentLoaded", async () => {
  const grid = document.getElementById("homeEventsGrid");
  if (!grid) return;

  const API_EVENTOS_URL = "../api/eventos.php";

  const escapeHtml = (value) =>
    String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

  const parseLocalDate = (isoDate) => {
    if (!isoDate) return null;
    const [year, month, day] = String(isoDate).split("-").map(Number);
    if (!year || !month || !day) return null;
    return new Date(year, month - 1, day);
  };

  const formatDate = (startIso, endIso) => {
    const start = parseLocalDate(startIso);
    const end = parseLocalDate(endIso || startIso);
    if (!start) return "";

    const dayMonth = new Intl.DateTimeFormat("es-CO", {
      day: "numeric",
      month: "long"
    });

    if (!end || start.getTime() === end.getTime()) {
      return dayMonth.format(start);
    }

    const sameMonth =
      start.getFullYear() === end.getFullYear() &&
      start.getMonth() === end.getMonth();

    if (sameMonth) {
      const month = new Intl.DateTimeFormat("es-CO", { month: "long" }).format(start);
      return `${start.getDate()}–${end.getDate()} de ${month}`;
    }

    return `${dayMonth.format(start)} – ${dayMonth.format(end)}`;
  };

  const createExcerpt = (text, maxLength = 92) => {
    const clean = String(text ?? "").replace(/\s+/g, " ").trim();
    if (!clean) return "";
    if (clean.length <= maxLength) return clean;

    const partial = clean.slice(0, maxLength);
    const lastSpace = partial.lastIndexOf(" ");
    const safeEnd = lastSpace > 62 ? lastSpace : maxLength;
    return `${partial.slice(0, safeEnd).trim()}...`;
  };

  const renderEmptyState = (message) => {
    grid.innerHTML = `
      <div class="home-events-empty">
        <p>${escapeHtml(message)}</p>
        <a href="eventos.html" class="home-events-all">
          Consultar agenda completa
          <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    `;
  };

  try {
    const response = await fetch(API_EVENTOS_URL, {
      method: "GET",
      headers: { Accept: "application/json" },
      cache: "no-store"
    });

    if (!response.ok) {
      throw new Error(`La API de eventos respondió con estado ${response.status}`);
    }

    const eventos = await response.json();
    if (!Array.isArray(eventos)) {
      throw new Error("La respuesta de la API de eventos no tiene el formato esperado.");
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const proximosEventos = eventos
      .filter((evento) => {
        const fechaFin = parseLocalDate(evento.fecha_fin || evento.fecha_inicio);
        return fechaFin && fechaFin >= today;
      })
      .sort((a, b) => parseLocalDate(a.fecha_inicio) - parseLocalDate(b.fecha_inicio))
      .slice(0, 3);

    if (proximosEventos.length === 0) {
      renderEmptyState("No hay próximos eventos programados en este momento.");
      return;
    }

    grid.innerHTML = proximosEventos.map((evento) => {
      const title = escapeHtml(evento.titulo);
      const category = escapeHtml(evento.categoria || "Actividad bibliotecaria");
      const dateText = escapeHtml(formatDate(evento.fecha_inicio, evento.fecha_fin));
      const description = escapeHtml(createExcerpt(evento.descripcion));
      const imageUrl = evento.imagen_url ? escapeHtml(evento.imagen_url) : "assets/img/logobiblio.png";

      return `
        <article class="home-event-card">
          <div class="home-event-visual">
            <img src="${imageUrl}" alt="${title}" loading="lazy">
          </div>

          <div class="home-event-content">
            <span class="home-event-date">${dateText}</span>
            <span class="home-event-category" title="${category}">${category}</span>
            <h3>${title}</h3>
            <p>${description}</p>
            <a href="eventos.html" class="home-event-card-link" aria-label="Consultar ${title} en la página de eventos">
              Conocer más
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </article>
      `;
    }).join("");

  } catch (error) {
    console.error("No fue posible cargar los eventos del Home:", error);
    renderEmptyState("No fue posible cargar la agenda de eventos.");
  }
});

