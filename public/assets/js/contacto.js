// =========================================================
// contacto.js
// Lógica exclusiva de la vista Contacto
// Encabezado administrable + validación + envío al backend
// =========================================================

async function cargarEncabezadoContacto() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=contacto", {
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
  cargarEncabezadoContacto();

  const API_URL = "../api/contacto.php";

  const form = document.getElementById("contactoForm");
  const nombre = document.getElementById("nombre");
  const correo = document.getElementById("correo");
  const telefono = document.getElementById("telefono");
  const asunto = document.getElementById("asunto");
  const mensaje = document.getElementById("mensaje");
  const politica = document.getElementById("politica");
  const contadorMensaje = document.getElementById("contadorMensaje");
  const formStatus = document.getElementById("formStatus");
  const searchForm = document.querySelector(".search-contacto");
  const submitButton = form?.querySelector('button[type="submit"]');

  if (searchForm) {
    searchForm.addEventListener("submit", (event) => {
      event.preventDefault();
    });
  }

  if (!form) return;

  if (mensaje && contadorMensaje) {
    const actualizarContador = () => {
      contadorMensaje.textContent = `${mensaje.value.length} / 1000`;
    };

    mensaje.addEventListener("input", actualizarContador);
    actualizarContador();
  }

  nombre?.addEventListener("blur", validarNombre);
  correo?.addEventListener("blur", validarCorreo);
  telefono?.addEventListener("blur", validarTelefono);
  asunto?.addEventListener("blur", validarAsunto);
  mensaje?.addEventListener("blur", validarMensaje);
  politica?.addEventListener("change", validarPolitica);

  nombre?.addEventListener("input", () => limpiarError(nombre));
  correo?.addEventListener("input", () => limpiarError(correo));
  telefono?.addEventListener("input", () => limpiarError(telefono));
  asunto?.addEventListener("input", () => limpiarError(asunto));
  mensaje?.addEventListener("input", () => limpiarError(mensaje));
  politica?.addEventListener("change", limpiarErrorCheckbox);

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    limpiarErroresGenerales();
    mostrarEstado("", "");

    const formularioValido =
      validarNombre() &&
      validarCorreo() &&
      validarTelefono() &&
      validarAsunto() &&
      validarMensaje() &&
      validarPolitica();

    if (!formularioValido) {
      mostrarEstado(
        "Por favor corrige los campos marcados antes de continuar.",
        "error"
      );
      return;
    }

    const textoOriginal = submitButton?.textContent || "Enviar mensaje";

    try {
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = "Enviando...";
      }

      const formData = new FormData();

      formData.append("nombre", nombre.value.trim());
      formData.append("correo", correo.value.trim());
      formData.append("telefono", telefono.value.trim());
      formData.append("asunto", asunto.value.trim());
      formData.append("mensaje", mensaje.value.trim());

      const response = await fetch(API_URL, {
        method: "POST",
        body: formData,
        headers: {
          Accept: "application/json"
        }
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        const errores = Array.isArray(data.errors)
          ? data.errors.join(" ")
          : "";

        throw new Error(
          errores ||
          data.message ||
          "No fue posible enviar el mensaje."
        );
      }

      mostrarEstado(
        data.message || "Tu mensaje fue recibido correctamente.",
        "success"
      );

      form.reset();

      if (contadorMensaje) {
        contadorMensaje.textContent = "0 / 1000";
      }

      limpiarErroresGenerales();
    } catch (error) {
      console.error(
        "Error al enviar el formulario de contacto:",
        error
      );

      mostrarEstado(
        error.message ||
        "No fue posible enviar el mensaje. Intenta nuevamente.",
        "error"
      );
    } finally {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = textoOriginal;
      }
    }
  });

  function validarNombre() {
    const valor = nombre?.value.trim() ?? "";

    if (!valor) {
      mostrarError(nombre, "Por favor ingresa tu nombre completo.");
      return false;
    }

    if (valor.length < 3) {
      mostrarError(nombre, "El nombre debe tener al menos 3 caracteres.");
      return false;
    }

    if (valor.length > 150) {
      mostrarError(nombre, "El nombre no puede superar los 150 caracteres.");
      return false;
    }

    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/.test(valor)) {
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
      mostrarError(correo, "Por favor ingresa tu correo electrónico.");
      return false;
    }

    if (valor.length > 180) {
      mostrarError(correo, "El correo no puede superar los 180 caracteres.");
      return false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(valor)) {
      mostrarError(
        correo,
        "Ingresa un correo válido. Ejemplo: nombre@correo.com"
      );
      return false;
    }

    limpiarError(correo);
    return true;
  }

  function validarTelefono() {
    const valor = telefono?.value.trim() ?? "";

    if (!valor) {
      limpiarError(telefono);
      return true;
    }

    if (valor.length > 30) {
      mostrarError(
        telefono,
        "El teléfono no puede superar los 30 caracteres."
      );
      return false;
    }

    if (!/^[\d\s+\-()]+$/.test(valor)) {
      mostrarError(
        telefono,
        "El teléfono solo puede contener números y símbolos como + - ( )."
      );
      return false;
    }

    const soloDigitos = valor.replace(/\D/g, "");

    if (soloDigitos.length < 7 || soloDigitos.length > 15) {
      mostrarError(
        telefono,
        "El teléfono debe tener entre 7 y 15 dígitos."
      );
      return false;
    }

    limpiarError(telefono);
    return true;
  }

  function validarAsunto() {
    const valor = asunto?.value.trim() ?? "";

    if (!valor) {
      mostrarError(
        asunto,
        "Por favor ingresa el asunto del mensaje."
      );
      return false;
    }

    if (valor.length < 3) {
      mostrarError(
        asunto,
        "El asunto debe tener al menos 3 caracteres."
      );
      return false;
    }

    if (valor.length > 180) {
      mostrarError(
        asunto,
        "El asunto no puede superar los 180 caracteres."
      );
      return false;
    }

    limpiarError(asunto);
    return true;
  }

  function validarMensaje() {
    const valor = mensaje?.value.trim() ?? "";

    if (!valor) {
      mostrarError(mensaje, "Por favor escribe tu mensaje.");
      return false;
    }

    if (valor.length < 10) {
      mostrarError(
        mensaje,
        "El mensaje debe tener al menos 10 caracteres."
      );
      return false;
    }

    if (valor.length > 1000) {
      mostrarError(
        mensaje,
        "El mensaje no puede superar los 1000 caracteres."
      );
      return false;
    }

    limpiarError(mensaje);
    return true;
  }

  function validarPolitica() {
    const checkboxError = document.querySelector(".checkbox-error");

    if (!politica?.checked) {
      if (checkboxError) {
        checkboxError.textContent =
          "Debes aceptar la política de tratamiento de datos.";
      }
      return false;
    }

    if (checkboxError) {
      checkboxError.textContent = "";
    }

    return true;
  }

  function mostrarError(campo, mensajeError) {
    if (!campo) return;

    const grupo = campo.closest(".form-group");

    if (!grupo) return;

    const error = grupo.querySelector(".error-message");

    if (error) {
      error.textContent = mensajeError;
    }

    campo.setAttribute("aria-invalid", "true");
  }

  function limpiarError(campo) {
    if (!campo) return;

    const grupo = campo.closest(".form-group");

    if (!grupo) return;

    const error = grupo.querySelector(".error-message");

    if (error) {
      error.textContent = "";
    }

    campo.removeAttribute("aria-invalid");
  }

  function limpiarErrorCheckbox() {
    const checkboxError = document.querySelector(".checkbox-error");

    if (checkboxError) {
      checkboxError.textContent = "";
    }
  }

  function limpiarErroresGenerales() {
    form.querySelectorAll(".error-message").forEach((error) => {
      error.textContent = "";
    });

    form.querySelectorAll("input, textarea").forEach((campo) => {
      campo.removeAttribute("aria-invalid");
    });

    limpiarErrorCheckbox();
  }

  function mostrarEstado(texto, tipo) {
    if (!formStatus) return;

    formStatus.textContent = texto;
    formStatus.classList.remove("success", "error");

    if (tipo) {
      formStatus.classList.add(tipo);
    }
  }
});
