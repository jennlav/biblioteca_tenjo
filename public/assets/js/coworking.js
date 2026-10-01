// =========================================================

// coworking.js

// Validación front-end de la solicitud de espacios

// + previsualización dinámica de cada espacio

// + envío real al backend PHP

// =========================================================

async function cargarEncabezadoCoworking() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=coworking", {
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
    // Se mantiene el contenido estático definido en el HTML.
  }
}

document.addEventListener("DOMContentLoaded", () => {
  cargarEncabezadoCoworking();

  const form = document.getElementById("coworkingForm");

  const searchForm = document.querySelector(".search-coworking");

  const nombre = document.getElementById("nombreCoworking");

  const correo = document.getElementById("correoCoworking");

  const telefono = document.getElementById("telefonoCoworking");

  const espacio = document.getElementById("espacioReserva");

  const fecha = document.getElementById("fechaReserva");

  const horario = document.getElementById("horarioReserva");

  const tipoUso = document.getElementById("tipoUso");

  const cantidad = document.getElementById("cantidadPersonas");

  const detalle = document.getElementById("detalleReserva");

  const politica = document.getElementById("politicaCoworking");

  const contador = document.getElementById("contadorDetalle");

  const status = document.getElementById("coworkingFormStatus");

  const espacioPreview = document.getElementById("espacioPreview");

  const espacioPreviewTitulo = document.getElementById("espacioPreviewTitulo");

  const espacioImagen1 = document.getElementById("espacioImagen1");

  const espacioImagen2 = document.getElementById("espacioImagen2");

  const espacios = {

    "box-lectura": {

      titulo: "Box de lectura",

      imagenes: [

        "assets/img/coworking/box1.JPG",

        "assets/img/coworking/box2.JPG"

      ]

    },

    "sala-alas-papel": {

      titulo: "Sala Alas de Papel",

      imagenes: [

        "assets/img/coworking/alas1.JPEG",

        "assets/img/coworking/alas2.JPG"

      ]

    },

    "sala-trabajo": {

      titulo: "Sala de trabajo",

      imagenes: [

        "assets/img/coworking/trabajo1.JPG",

        "assets/img/coworking/trabajo2.JPG"

      ]

    },

    "computadores": {

      titulo: "Préstamo de Computadores",

      imagenes: [

        "assets/img/coworking/computadores1.JPG",

        "assets/img/coworking/computadores2.JPG"

      ]

    }

  };

  if (searchForm) {

    searchForm.addEventListener("submit", (event) => {

      event.preventDefault();

    });

  }

  if (!form) {

    return;

  }

  function actualizarVistaEspacio() {

    const valor = espacio?.value ?? "";

    const datos = espacios[valor];

    if (!datos) {

      if (espacioPreview) {

        espacioPreview.hidden = true;

      }

      if (espacioPreviewTitulo) {

        espacioPreviewTitulo.textContent = "";

      }

      if (espacioImagen1) {

        espacioImagen1.removeAttribute("src");

        espacioImagen1.alt = "";

      }

      if (espacioImagen2) {

        espacioImagen2.removeAttribute("src");

        espacioImagen2.alt = "";

      }

      return;

    }

    if (espacioPreviewTitulo) {

      espacioPreviewTitulo.textContent = datos.titulo;

    }

    if (espacioImagen1) {

      espacioImagen1.src = datos.imagenes[0];

      espacioImagen1.alt = `${datos.titulo} - vista 1`;

    }

    if (espacioImagen2) {

      espacioImagen2.src = datos.imagenes[1];

      espacioImagen2.alt = `${datos.titulo} - vista 2`;

    }

    if (espacioPreview) {

      espacioPreview.hidden = false;

    }

  }

  espacio?.addEventListener("change", () => {

    actualizarVistaEspacio();

    validarSeleccion(

      espacio,

      "Selecciona el espacio que deseas solicitar."

    );

  });

  // Evita seleccionar fechas anteriores.

  if (fecha) {

    const hoy = new Date();

    const anio = hoy.getFullYear();

    const mes = String(hoy.getMonth() + 1).padStart(2, "0");

    const dia = String(hoy.getDate()).padStart(2, "0");

    fecha.min = `${anio}-${mes}-${dia}`;

  }

  if (detalle && contador) {

    const actualizarContador = () => {

      contador.textContent = `${detalle.value.length} / 800`;

    };

    detalle.addEventListener("input", actualizarContador);

    actualizarContador();

  }

  const campos = [

    nombre,

    correo,

    telefono,

    espacio,

    fecha,

    horario,

    tipoUso,

    cantidad,

    detalle

  ];

  campos.forEach((campo) => {

    campo?.addEventListener("blur", () => validarCampo(campo));

    campo?.addEventListener("change", () => validarCampo(campo));

    campo?.addEventListener("input", () => limpiarError(campo));

  });

  politica?.addEventListener("change", () => {

    validarPolitica();

  });

  /*

  =========================================================

  ENVÍO REAL AL BACKEND

  =========================================================

  */

  form.addEventListener("submit", async (event) => {

    event.preventDefault();

    limpiarEstado();

    const resultados = [

      validarNombre(),

      validarCorreo(),

      validarTelefono(),

      validarSeleccion(

        espacio,

        "Selecciona el espacio que deseas solicitar."

      ),

      validarFecha(),

      validarSeleccion(

        horario,

        "Selecciona un horario."

      ),

      validarSeleccion(

        tipoUso,

        "Selecciona el tipo de uso."

      ),

      validarSeleccion(

        cantidad,

        "Selecciona la cantidad de personas."

      ),

      validarDetalle(),

      validarPolitica()

    ];

    const formularioValido =

      resultados.every(Boolean);

    if (!formularioValido) {

      mostrarEstado(

        "Por favor corrige los campos marcados antes de enviar la solicitud.",

        "error"

      );

      return;

    }

    const botonEnviar =

      form.querySelector(".btn-coworking-enviar");

    const textoOriginal =

      botonEnviar?.textContent ?? "Enviar Solicitud";

    try {

      if (botonEnviar) {

        botonEnviar.disabled = true;

        botonEnviar.textContent = "Enviando...";

      }

      const formData =

        new FormData();

      formData.append(

        "nombreCoworking",

        nombre.value.trim()

      );

      formData.append(

        "correoCoworking",

        correo.value.trim()

      );

      formData.append(

        "telefonoCoworking",

        telefono.value.trim()

      );

      formData.append(

        "espacioReserva",

        espacio.value

      );

      formData.append(

        "fechaReserva",

        fecha.value

      );

      formData.append(

        "horarioReserva",

        horario.value

      );

      formData.append(

        "tipoUso",

        tipoUso.value

      );

      formData.append(

        "cantidadPersonas",

        cantidad.value

      );

      formData.append(

        "detalleReserva",

        detalle.value.trim()

      );

      const response =

        await fetch(

          "../api/solicitudes_espacios.php",

          {

            method: "POST",

            body: formData,

            headers: {

              Accept: "application/json"

            }

          }

        );

      let data;

      try {

        data =

          await response.json();

      } catch {

        throw new Error(

          "El servidor devolvió una respuesta que no pudo procesarse."

        );

      }

      if (

        !response.ok ||

        !data.success

      ) {

        const detalleErrores =

          Array.isArray(data.errors)

            ? data.errors.join(" ")

            : "";

        throw new Error(

          detalleErrores ||

          data.message ||

          "No fue posible registrar la solicitud."

        );

      }

      const numeroSolicitud =

        data.solicitud

          ? ` Número de solicitud: ${data.solicitud}.`

          : "";

      mostrarEstado(

        (data.message ||

          "Tu solicitud de espacio fue recibida correctamente.") +

          numeroSolicitud,

        "success"

      );

      form.reset();

      if (contador) {

        contador.textContent = "0 / 800";

      }

      if (espacioPreview) {

        espacioPreview.hidden = true;

      }

      if (espacioPreviewTitulo) {

        espacioPreviewTitulo.textContent = "";

      }

      if (espacioImagen1) {

        espacioImagen1.removeAttribute("src");

        espacioImagen1.alt = "";

      }

      if (espacioImagen2) {

        espacioImagen2.removeAttribute("src");

        espacioImagen2.alt = "";

      }

    } catch (error) {

      console.error(

        "Error al enviar la solicitud de espacio:",

        error

      );

      mostrarEstado(

        error.message ||

        "No fue posible enviar la solicitud. Intenta nuevamente.",

        "error"

      );

    } finally {

      if (botonEnviar) {

        botonEnviar.disabled = false;

        botonEnviar.textContent = textoOriginal;

      }

    }

  });

  function validarCampo(campo) {

    if (!campo) {

      return false;

    }

    switch (campo.id) {

      case "nombreCoworking":

        return validarNombre();

      case "correoCoworking":

        return validarCorreo();

      case "telefonoCoworking":

        return validarTelefono();

      case "espacioReserva":

        return validarSeleccion(

          campo,

          "Selecciona el espacio que deseas solicitar."

        );

      case "fechaReserva":

        return validarFecha();

      case "horarioReserva":

        return validarSeleccion(

          campo,

          "Selecciona un horario."

        );

      case "tipoUso":

        return validarSeleccion(

          campo,

          "Selecciona el tipo de uso."

        );

      case "cantidadPersonas":

        return validarSeleccion(

          campo,

          "Selecciona la cantidad de personas."

        );

      case "detalleReserva":

        return validarDetalle();

      default:

        return true;

    }

  }

  function validarNombre() {

    const valor = nombre?.value.trim() ?? "";

    if (!valor) {

      mostrarError(

        nombre,

        "Ingresa tu nombre completo."

      );

      return false;

    }

    if (valor.length < 3) {

      mostrarError(

        nombre,

        "El nombre debe tener al menos 3 caracteres."

      );

      return false;

    }

    if (

      !/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/.test(valor)

    ) {

      mostrarError(

        nombre,

        "El nombre solo debe contener letras y espacios."

      );

      return false;

    }

    limpiarError(nombre);

    return true;

  }

  function validarCorreo() {

    const valor = correo?.value.trim() ?? "";

    if (!valor) {

      mostrarError(

        correo,

        "Ingresa tu correo electrónico."

      );

      return false;

    }

    if (

      !/^[^\s@]+@[^\s@]+.[^\s@]{2,}$/.test(valor)

    ) {

      mostrarError(

        correo,

        "Ingresa un correo electrónico válido."

      );

      return false;

    }

    limpiarError(correo);

    return true;

  }

  function validarTelefono() {

    const valor = telefono?.value.trim() ?? "";

    if (!valor) {

      mostrarError(

        telefono,

        "Ingresa tu teléfono o celular."

      );

      return false;

    }

    if (!/^[\d\s+()\-]+$/.test(valor)) {

      mostrarError(

        telefono,

        "El teléfono solo puede contener números y símbolos como + - ( )."

      );

      return false;

    }

    const digitos =

      valor.replace(/\D/g, "");

    if (

      digitos.length < 7 ||

      digitos.length > 15

    ) {

      mostrarError(

        telefono,

        "El teléfono debe tener entre 7 y 15 dígitos."

      );

      return false;

    }

    limpiarError(telefono);

    return true;

  }

  function validarFecha() {

    const valor = fecha?.value ?? "";

    if (!valor) {

      mostrarError(

        fecha,

        "Selecciona una fecha para la reserva."

      );

      return false;

    }

    const fechaSeleccionada =

      new Date(`${valor}T00:00:00`);

    const hoy = new Date();

    hoy.setHours(0, 0, 0, 0);

    if (fechaSeleccionada < hoy) {

      mostrarError(

        fecha,

        "La fecha no puede ser anterior al día de hoy."

      );

      return false;

    }

    const diaSemana =

      fechaSeleccionada.getDay();

    if (diaSemana === 0) {

      mostrarError(

        fecha,

        "La biblioteca no presta este servicio los domingos."

      );

      return false;

    }

    limpiarError(fecha);

    return true;

  }

  function validarSeleccion(campo, mensaje) {

    if (!campo?.value) {

      mostrarError(campo, mensaje);

      return false;

    }

    limpiarError(campo);

    return true;

  }

  function validarDetalle() {

    const valor = detalle?.value.trim() ?? "";

    if (!valor) {

      mostrarError(

        detalle,

        "Describe el uso que darás al espacio."

      );

      return false;

    }

    if (valor.length < 15) {

      mostrarError(

        detalle,

        "La descripción debe tener al menos 15 caracteres."

      );

      return false;

    }

    if (valor.length > 800) {

      mostrarError(

        detalle,

        "La descripción no puede superar 800 caracteres."

      );

      return false;

    }

    limpiarError(detalle);

    return true;

  }

  function validarPolitica() {

    const error =

      document.querySelector(".checkbox-error");

    if (!politica?.checked) {

      if (error) {

        error.textContent =

          "Debes aceptar la política de tratamiento de datos.";

      }

      return false;

    }

    if (error) {

      error.textContent = "";

    }

    return true;

  }

  function mostrarError(campo, mensaje) {

    if (!campo) {

      return;

    }

    const grupo =

      campo.closest(".form-group");

    const error =

      grupo?.querySelector(".error-message");

    if (error) {

      error.textContent = mensaje;

    }

    campo.setAttribute(

      "aria-invalid",

      "true"

    );

  }

  function limpiarError(campo) {

    if (!campo) {

      return;

    }

    const grupo =

      campo.closest(".form-group");

    const error =

      grupo?.querySelector(".error-message");

    if (error) {

      error.textContent = "";

    }

    campo.removeAttribute("aria-invalid");

  }

  function limpiarEstado() {

    form

      .querySelectorAll(".error-message")

      .forEach((elemento) => {

        elemento.textContent = "";

      });

    form

      .querySelectorAll("[aria-invalid]")

      .forEach((campo) => {

        campo.removeAttribute("aria-invalid");

      });

    if (status) {

      status.textContent = "";

      status.classList.remove(

        "error",

        "success"

      );

    }

  }

  function mostrarEstado(mensaje, tipo) {

    if (!status) {

      return;

    }

    status.textContent = mensaje;

    status.classList.remove(

      "error",

      "success"

    );

    status.classList.add(tipo);

  }

});
