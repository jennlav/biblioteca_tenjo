// =========================================================
// AVISO MODAL ADMINISTRABLE DEL HOME
// Construye el componente y consume ../api/popup.php.
// Incluye validación de URL, accesibilidad y adaptación responsive.
// =========================================================



document.addEventListener("DOMContentLoaded", async () => {

  const API_POPUP_URL = "../api/popup.php";



  /* Estilos encapsulados requeridos por el componente modal. */



  const style = document.createElement("style");



  style.textContent = `

    body.home-popup-open {

      overflow: hidden;

    }



    .home-popup {

      position: fixed;

      inset: 0;

      z-index: 5000;

      display: flex;

      align-items: center;

      justify-content: center;

      padding: 20px;

      background: rgba(13, 35, 25, 0.56);

      backdrop-filter: blur(5px);

      opacity: 0;

      visibility: hidden;

      pointer-events: none;

      transition:

        opacity 0.2s ease,

        visibility 0.2s ease;

    }



    .home-popup.is-visible {

      opacity: 1;

      visibility: visible;

      pointer-events: auto;

    }



    .home-popup-dialog {

      position: relative;

      width: min(100%, 900px);

      height: min(82vh, 650px);

      min-height: 440px;

      display: grid;

      grid-template-columns: 47% 53%;

      overflow: hidden;

      border: 1px solid rgba(0, 68, 32, 0.12);

      border-radius: 24px;

      background: #fff;

      box-shadow: 0 28px 70px rgba(0, 0, 0, 0.30);

      transform: translateY(14px) scale(0.985);

      transition: transform 0.2s ease;

    }



    .home-popup.is-visible .home-popup-dialog {

      transform: translateY(0) scale(1);

    }



    .home-popup-dialog.home-popup-no-image {

      width: min(100%, 650px);

      grid-template-columns: 1fr;

    }



    .home-popup-close {

      position: absolute;

      top: 14px;

      right: 14px;

      z-index: 10;

      width: 42px;

      height: 42px;

      display: inline-flex;

      align-items: center;

      justify-content: center;

      border: 0;

      border-radius: 50%;

      background: rgba(255, 255, 255, 0.96);

      color: var(--color-primary, #0b5b34);

      font-size: 1.05rem;

      cursor: pointer;

      box-shadow: 0 5px 16px rgba(0, 0, 0, 0.14);

    }



    .home-popup-media {

      width: 100%;

      height: 100%;

      min-width: 0;

      min-height: 0;

      overflow: hidden;

      background: #eef3ef;

    }



    .home-popup-media img {

      width: 100%;

      height: 100%;

      display: block;

      object-fit: cover;

      object-position: center;

    }



    .home-popup-content {

      min-width: 0;

      min-height: 0;

      height: 100%;

      display: flex;

      flex-direction: column;

      justify-content: flex-start;

      overflow-y: auto;

      padding: 52px 44px 34px;

      scrollbar-width: thin;

      scrollbar-color: rgba(0,68,32,.32) rgba(0,68,32,.06);

    }



    .home-popup-content::-webkit-scrollbar {

      width: 8px;

    }



    .home-popup-content::-webkit-scrollbar-track {

      background: rgba(0, 68, 32, 0.05);

      border-radius: 999px;

    }



    .home-popup-content::-webkit-scrollbar-thumb {

      background: rgba(0, 68, 32, 0.28);

      border-radius: 999px;

    }



    .home-popup-kicker {

      display: block;

      margin: 0 0 10px;

      color: var(--color-accent-dark, #b59700);

      font-family: var(--font-ui, Arial, sans-serif);

      font-size: 0.78rem;

      font-weight: 800;

      letter-spacing: 0.09em;

      text-transform: uppercase;

      flex-shrink: 0;

    }



    .home-popup-content h2 {

      margin: 0 0 16px;

      color: var(--color-primary, #0b5b34);

      font-family: var(--font-heading, Arial, sans-serif);

      font-size: clamp(2rem, 4vw, 3.05rem);

      line-height: 1;

      flex-shrink: 0;

    }



    .home-popup-content p {

      margin: 0;

      color: var(--color-neutral-700, #59635f);

      font-size: 1rem;

      line-height: 1.65;

      white-space: pre-line;

    }



    .home-popup-actions {

      display: block;

      margin-top: 24px;

      padding-bottom: 4px;

      flex-shrink: 0;

    }



    .home-popup-actions[hidden] {

      display: none !important;

    }



    .home-popup-button {

      display: inline-flex !important;

      align-items: center;

      justify-content: center;

      min-width: 150px;

      padding: 13px 24px;

      border: 0;

      border-radius: 12px;

      background: linear-gradient(

        180deg,

        var(--color-accent-soft, #eac108) 0%,

        var(--color-accent-dark, #b59700) 100%

      );

      color: #fff !important;

      font-family: var(--font-ui, Arial, sans-serif);

      font-size: 0.96rem;

      font-weight: 800;

      text-decoration: none !important;

      box-shadow: 0 10px 22px rgba(234, 193, 8, 0.25);

    }



    .home-popup-button:hover,

    .home-popup-button:focus-visible {

      color: #fff !important;

      transform: translateY(-1px);

    }



    @media (max-width: 767px) {

      .home-popup {

        align-items: flex-start;

        padding: 12px;

        overflow-y: auto;

      }



      .home-popup-dialog {

        width: min(100%, 540px);

        height: auto;

        min-height: 0;

        max-height: none;

        grid-template-columns: 1fr;

        margin: 24px auto;

        border-radius: 20px;

      }



      .home-popup-media {

        height: 250px;

      }



      .home-popup-content {

        height: auto;

        overflow: visible;

        padding: 30px 24px 28px;

      }



      .home-popup-content h2 {

        font-size: 2rem;

      }

    }



    @media (max-width: 480px) {

      .home-popup-dialog {

        margin: 14px auto;

        border-radius: 17px;

      }



      .home-popup-close {

        top: 10px;

        right: 10px;

        width: 38px;

        height: 38px;

      }



      .home-popup-media {

        height: 210px;

      }



      .home-popup-content {

        padding: 26px 20px 24px;

      }



      .home-popup-content h2 {

        font-size: 1.75rem;

      }



      .home-popup-content p {

        font-size: 0.94rem;

      }



      .home-popup-button {

        width: 100%;

      }

    }

  `;



  document.head.appendChild(style);



  /* Estructura semántica del modal insertada dinámicamente en el DOM. */



  const popup = document.createElement("div");



  popup.className = "home-popup";

  popup.id = "homePopup";

  popup.setAttribute("aria-hidden", "true");



  popup.innerHTML = `

    <div

      class="home-popup-dialog"

      role="dialog"

      aria-modal="true"

      aria-labelledby="homePopupTitle"

    >

      <button

        type="button"

        class="home-popup-close"

        id="homePopupClose"

        aria-label="Cerrar aviso"

      >

        <i class="bi bi-x-lg" aria-hidden="true"></i>

      </button>



      <div

        class="home-popup-media"

        id="homePopupMedia"

        hidden

      >

        <img

          id="homePopupImage"

          src=""

          alt=""

        >

      </div>



      <div class="home-popup-content">

        <span class="home-popup-kicker">

          Información de la Biblioteca

        </span>



        <h2 id="homePopupTitle"></h2>



        <p id="homePopupDescription"></p>



        <div

          class="home-popup-actions"

          id="homePopupActions"

          hidden

        >

          <a

            href="#"

            class="home-popup-button"

            id="homePopupButton"

          >

            Ver más

          </a>

        </div>

      </div>

    </div>

  `;



  document.body.appendChild(popup);



  const dialog = popup.querySelector(".home-popup-dialog");

  const closeButton = document.getElementById("homePopupClose");

  const title = document.getElementById("homePopupTitle");

  const description = document.getElementById("homePopupDescription");

  const media = document.getElementById("homePopupMedia");

  const image = document.getElementById("homePopupImage");

  const actions = document.getElementById("homePopupActions");

  const button = document.getElementById("homePopupButton");



  /* Control de estado, foco y cierre accesible del modal. */



  const cerrarPopup = () => {

    popup.classList.remove("is-visible");

    popup.setAttribute("aria-hidden", "true");

    document.body.classList.remove("home-popup-open");

  };



  const abrirPopup = () => {

    popup.classList.add("is-visible");

    popup.setAttribute("aria-hidden", "false");

    document.body.classList.add("home-popup-open");



    setTimeout(() => {

      closeButton.focus();

    }, 80);

  };



  closeButton.addEventListener("click", cerrarPopup);



  popup.addEventListener("click", (event) => {

    if (event.target === popup) {

      cerrarPopup();

    }

  });



  document.addEventListener("keydown", (event) => {

    if (

      event.key === "Escape" &&

      popup.classList.contains("is-visible")

    ) {

      cerrarPopup();

    }

  });



  /* Normalización de destinos: solo se permiten protocolos HTTP/HTTPS. */



  const normalizarUrl = (url) => {

    const valor = String(url ?? "").trim();



    if (!valor) {

      return "";

    }



    try {

      const parsed = new URL(valor, window.location.href);



      if (

        parsed.protocol === "http:" ||

        parsed.protocol === "https:"

      ) {

        return parsed.href;

      }

    } catch (error) {

      console.error("URL no válida en popup:", error);

    }



    return "";

  };



  /* Consulta del aviso activo publicado por el backend. */



  try {

    const response = await fetch(API_POPUP_URL, {

      method: "GET",

      headers: {

        Accept: "application/json"

      },

      cache: "no-store"

    });



    if (!response.ok) {

      throw new Error(

        `La API del popup respondió con estado ${response.status}`

      );

    }



    const data = await response.json();



    if (

      !data ||

      data.success !== true ||

      !data.popup

    ) {

      return;

    }



    const aviso = data.popup;



    title.textContent = String(aviso.titulo ?? "").trim();

    description.textContent = String(aviso.descripcion ?? "").trim();



    /* Configuración opcional del recurso gráfico asociado al aviso. */



    if (aviso.imagen_url) {

      image.src = aviso.imagen_url;

      image.alt = aviso.titulo

        ? `Imagen del aviso: ${aviso.titulo}`

        : "Imagen del aviso de la Biblioteca";



      media.hidden = false;

      dialog.classList.remove("home-popup-no-image");

    } else {

      image.removeAttribute("src");

      image.alt = "";

      media.hidden = true;

      dialog.classList.add("home-popup-no-image");

    }



    /* Configuración opcional de la acción principal del aviso. */



    const textoBoton =

      String(aviso.boton_texto ?? "").trim();



    const urlBoton =

      normalizarUrl(aviso.boton_url);
if (

      textoBoton.length > 0 &&

      urlBoton.length > 0

    ) {

      button.textContent = textoBoton;

      button.href = urlBoton;



      const destino = new URL(urlBoton);



      if (

        destino.origin !==

        window.location.origin

      ) {

        button.target = "_blank";

        button.rel = "noopener noreferrer";

      } else {

        button.removeAttribute("target");

        button.removeAttribute("rel");

      }



      actions.hidden = false;

      actions.style.display = "block";



    } else {

      actions.hidden = true;

      actions.style.display = "none";

    }



    abrirPopup();



  } catch (error) {

    console.error(

      "No fue posible cargar el popup del Home:",

      error

    );

  }

});