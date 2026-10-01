// =========================================================
// ENCABEZADO ADMINISTRABLE
// Cápsula + título + descripción
// =========================================================

async function cargarEncabezadoPQRS() {
  const etiqueta = document.getElementById("encabezado-etiqueta");
  const titulo = document.getElementById("encabezado-titulo");
  const descripcion = document.getElementById("encabezado-descripcion");

  try {
    const response = await fetch("../api/encabezados.php?vista=pqrs", {
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

// =========================================================

// pqrs.js

// Lógica exclusiva de la vista PQRSDF

// Validación + envío real al backend PHP

// =========================================================



document.addEventListener("DOMContentLoaded", () => {
  cargarEncabezadoPQRS();


  const API_URL = "../api/pqrs.php";



  const form = document.getElementById("pqrsForm");

  if (!form) return;



  const tipoSolicitante = document.getElementById("tipoSolicitante");

  const nombreCompleto = document.getElementById("nombreCompleto");

  const tipoDocumento = document.getElementById("tipoDocumento");

  const numeroDocumento = document.getElementById("numeroDocumento");

  const correoPQRS = document.getElementById("correoPQRS");

  const telefonoPQRS = document.getElementById("telefonoPQRS");

  const tipoPQRS = document.getElementById("tipoPQRS");

  const descripcionPQRS = document.getElementById("descripcionPQRS");

  const archivoPQRS = document.getElementById("archivoPQRS");

  const politicaPQRS = document.getElementById("politicaPQRS");

  const contadorDescripcion = document.getElementById("contadorDescripcion");

  const pqrsStatus = document.getElementById("pqrsStatus");

  const fileDropzone = document.getElementById("fileDropzone");

  const fileInfo = document.getElementById("fileInfo");

  const submitButton = form.querySelector('button[type="submit"]');



  const MAX_FILE_SIZE = 8 * 1024 * 1024;



  const ALLOWED_EXTENSIONS = [

    "pdf",

    "doc",

    "docx",

    "xls",

    "xlsx",

    "jpg",

    "jpeg",

    "png"

  ];



  /*

  ========================================================

  BUSCADOR SUPERIOR

  ========================================================

  */



  const searchForm = document.querySelector(".search-pqrs");



  if (searchForm) {

    searchForm.addEventListener("submit", (event) => {

      event.preventDefault();

    });

  }



  /*

  ========================================================

  CONTADOR DESCRIPCIÓN

  ========================================================

  */



  if (descripcionPQRS && contadorDescripcion) {

    const updateCounter = () => {

      contadorDescripcion.textContent =

        `${descripcionPQRS.value.length} / 1200`;

    };



    descripcionPQRS.addEventListener(

      "input",

      updateCounter

    );



    updateCounter();

  }



  /*

  ========================================================

  DRAG & DROP DE ARCHIVOS

  ========================================================

  */



  if (fileDropzone && archivoPQRS) {



    ["dragenter", "dragover"].forEach((eventName) => {



      fileDropzone.addEventListener(

        eventName,

        (event) => {



          event.preventDefault();

          event.stopPropagation();



          fileDropzone.classList.add(

            "dragover"

          );

        }

      );

    });



    ["dragleave", "drop"].forEach((eventName) => {



      fileDropzone.addEventListener(

        eventName,

        (event) => {



          event.preventDefault();

          event.stopPropagation();



          fileDropzone.classList.remove(

            "dragover"

          );

        }

      );

    });



    fileDropzone.addEventListener(

      "drop",

      (event) => {



        const files =

          event.dataTransfer.files;



        if (files.length > 0) {



          const dataTransfer =

            new DataTransfer();



          dataTransfer.items.add(

            files[0]

          );



          archivoPQRS.files =

            dataTransfer.files;



          validarArchivo();

        }

      }

    );



    archivoPQRS.addEventListener(

      "change",

      validarArchivo

    );

  }



  /*

  ========================================================

  VALIDACIONES EN TIEMPO REAL

  ========================================================

  */



  tipoSolicitante?.addEventListener(

    "change",

    () =>

      validarSelect(

        tipoSolicitante,

        "Selecciona tu perfil."

      )

  );



  nombreCompleto?.addEventListener(

    "blur",

    validarNombre

  );



  tipoDocumento?.addEventListener(

    "change",

    () =>

      validarSelect(

        tipoDocumento,

        "Selecciona el tipo de documento."

      )

  );



  numeroDocumento?.addEventListener(

    "blur",

    validarDocumento

  );



  correoPQRS?.addEventListener(

    "blur",

    validarCorreo

  );



  telefonoPQRS?.addEventListener(

    "blur",

    validarTelefono

  );



  tipoPQRS?.addEventListener(

    "change",

    () =>

      validarSelect(

        tipoPQRS,

        "Selecciona el tipo de PQRSDF."

      )

  );



  descripcionPQRS?.addEventListener(

    "blur",

    validarDescripcion

  );



  politicaPQRS?.addEventListener(

    "change",

    validarPolitica

  );



  [

    tipoSolicitante,

    nombreCompleto,

    tipoDocumento,

    numeroDocumento,

    correoPQRS,

    telefonoPQRS,

    tipoPQRS,

    descripcionPQRS

  ].forEach((campo) => {



    campo?.addEventListener(

      "input",

      () => limpiarError(campo)

    );



    campo?.addEventListener(

      "change",

      () => limpiarError(campo)

    );

  });



  politicaPQRS?.addEventListener(

    "change",

    limpiarErrorCheckbox

  );



  /*

  ========================================================

  ENVÍO REAL DEL FORMULARIO

  ========================================================

  */



  form.addEventListener(

    "submit",

    async (event) => {



      event.preventDefault();



      limpiarErroresGenerales();

      mostrarEstado("", "");



      const validaciones = [

        validarSelect(

          tipoSolicitante,

          "Selecciona tu perfil."

        ),



        validarNombre(),



        validarSelect(

          tipoDocumento,

          "Selecciona el tipo de documento."

        ),



        validarDocumento(),



        validarCorreo(),



        validarTelefono(),



        validarSelect(

          tipoPQRS,

          "Selecciona el tipo de PQRSDF."

        ),



        validarDescripcion(),



        validarArchivo(),



        validarPolitica()

      ];



      const esValido =

        validaciones.every(Boolean);



      if (!esValido) {



        mostrarEstado(

          "Por favor corrige los campos marcados antes de continuar.",

          "error"

        );



        return;

      }



      const textoOriginalBoton =

        submitButton?.textContent ??

        "Enviar Solicitud";



      try {



        if (submitButton) {



          submitButton.disabled = true;



          submitButton.textContent =

            "Enviando...";

        }



        /*

        ====================================================

        CONSTRUIR FormData

        ====================================================

        */



        const formData =

          new FormData();



        formData.append(

          "tipoSolicitante",

          tipoSolicitante.value

        );



        formData.append(

          "nombreCompleto",

          nombreCompleto.value.trim()

        );



        formData.append(

          "tipoDocumento",

          tipoDocumento.value

        );



        formData.append(

          "numeroDocumento",

          numeroDocumento.value.trim()

        );



        formData.append(

          "correoPQRS",

          correoPQRS.value.trim()

        );



        formData.append(

          "telefonoPQRS",

          telefonoPQRS.value.trim()

        );



        formData.append(

          "tipoPQRS",

          tipoPQRS.value

        );



        formData.append(

          "descripcionPQRS",

          descripcionPQRS.value.trim()

        );



        /*

        ====================================================

        ARCHIVO OPCIONAL

        ====================================================

        */



        if (

          archivoPQRS.files &&

          archivoPQRS.files.length > 0

        ) {



          formData.append(

            "archivoPQRS",

            archivoPQRS.files[0]

          );

        }



        /*

        ====================================================

        ENVIAR AL BACKEND

        ====================================================

        */



        const response =

          await fetch(

            API_URL,

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



        /*

        ====================================================

        VALIDAR RESPUESTA

        ====================================================

        */



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



        /*

        ====================================================

        ÉXITO

        ====================================================

        */



        const radicado =

          data.radicado

            ? ` Número de radicado: ${data.radicado}.`

            : "";



        mostrarEstado(

          (data.message ||

            "Tu solicitud fue recibida correctamente.") +

            radicado,

          "success"

        );



        /*

        ====================================================

        LIMPIAR FORMULARIO

        ====================================================

        */



        form.reset();



        if (contadorDescripcion) {

          contadorDescripcion.textContent =

            "0 / 1200";

        }



        if (fileInfo) {

          fileInfo.textContent = "";

        }



        limpiarErroresGenerales();



      } catch (error) {



        console.error(

          "Error al enviar PQRSDF:",

          error

        );



        mostrarEstado(

          error.message ||

          "No fue posible enviar la solicitud. Intenta nuevamente.",

          "error"

        );



      } finally {



        if (submitButton) {



          submitButton.disabled =

            false;



          submitButton.textContent =

            textoOriginalBoton;

        }

      }

    }

  );



  /*

  ========================================================

  VALIDAR SELECT

  ========================================================

  */



  function validarSelect(

    campo,

    mensaje

  ) {



    if (

      !campo ||

      !campo.value.trim()

    ) {



      mostrarError(

        campo,

        mensaje

      );



      return false;

    }



    limpiarError(campo);



    return true;

  }



  /*

  ========================================================

  VALIDAR NOMBRE

  ========================================================

  */



  function validarNombre() {



    const valor =

      nombreCompleto.value.trim();



    if (!valor) {



      mostrarError(

        nombreCompleto,

        "Por favor ingresa tu nombre completo."

      );



      return false;

    }



    if (valor.length < 3) {



      mostrarError(

        nombreCompleto,

        "El nombre debe tener al menos 3 caracteres."

      );



      return false;

    }



    if (valor.length > 150) {



      mostrarError(

        nombreCompleto,

        "El nombre no puede superar los 150 caracteres."

      );



      return false;

    }



    if (

      !/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/.test(

        valor

      )

    ) {



      mostrarError(

        nombreCompleto,

        "El nombre solo debe contener letras y espacios."

      );



      return false;

    }



    limpiarError(

      nombreCompleto

    );



    return true;

  }



  /*

  ========================================================

  VALIDAR DOCUMENTO

  ========================================================

  */



  function validarDocumento() {



    const valor =

      numeroDocumento.value.trim();



    if (!valor) {



      mostrarError(

        numeroDocumento,

        "Por favor ingresa el número de documento."

      );



      return false;

    }



    if (

      !/^[A-Za-z0-9\-]+$/.test(

        valor

      )

    ) {



      mostrarError(

        numeroDocumento,

        "El documento solo puede contener letras, números y guiones."

      );



      return false;

    }



    if (

      valor.length < 5 ||

      valor.length > 20

    ) {



      mostrarError(

        numeroDocumento,

        "El documento debe tener entre 5 y 20 caracteres."

      );



      return false;

    }



    limpiarError(

      numeroDocumento

    );



    return true;

  }



  /*

  ========================================================

  VALIDAR CORREO

  ========================================================

  */



  function validarCorreo() {



    const valor =

      correoPQRS.value.trim();



    if (!valor) {



      mostrarError(

        correoPQRS,

        "Por favor ingresa tu correo electrónico."

      );



      return false;

    }



    if (valor.length > 180) {



      mostrarError(

        correoPQRS,

        "El correo no puede superar los 180 caracteres."

      );



      return false;

    }



    if (

      !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(

        valor

      )

    ) {



      mostrarError(

        correoPQRS,

        "Ingresa un correo válido. Ejemplo: nombre@correo.com"

      );



      return false;

    }



    limpiarError(

      correoPQRS

    );



    return true;

  }



  /*

  ========================================================

  VALIDAR TELÉFONO

  ========================================================

  */



  function validarTelefono() {



    const valor =

      telefonoPQRS.value.trim();



    if (!valor) {



      mostrarError(

        telefonoPQRS,

        "Por favor ingresa tu teléfono o celular."

      );



      return false;

    }



    if (valor.length > 30) {



      mostrarError(

        telefonoPQRS,

        "El teléfono no puede superar los 30 caracteres."

      );



      return false;

    }



    if (

      !/[\d\s+\-()]+$/.test(

        valor

      )

    ) {



      mostrarError(

        telefonoPQRS,

        "El teléfono solo puede contener números y símbolos como + - ( )."

      );



      return false;

    }



    const digits =

      valor.replace(

        /\D/g,

        ""

      );



    if (

      digits.length < 7 ||

      digits.length > 15

    ) {



      mostrarError(

        telefonoPQRS,

        "El teléfono debe tener entre 7 y 15 dígitos."

      );



      return false;

    }



    limpiarError(

      telefonoPQRS

    );



    return true;

  }



  /*

  ========================================================

  VALIDAR DESCRIPCIÓN

  ========================================================

  */



  function validarDescripcion() {



    const valor =

      descripcionPQRS.value.trim();



    if (!valor) {



      mostrarError(

        descripcionPQRS,

        "Por favor escribe la descripción de tu solicitud."

      );



      return false;

    }



    if (valor.length < 15) {



      mostrarError(

        descripcionPQRS,

        "La descripción debe tener al menos 15 caracteres."

      );



      return false;

    }



    if (valor.length > 1200) {



      mostrarError(

        descripcionPQRS,

        "La descripción no puede superar los 1200 caracteres."

      );



      return false;

    }



    limpiarError(

      descripcionPQRS

    );



    return true;

  }



  /*

  ========================================================

  VALIDAR ARCHIVO

  ========================================================

  */



  function validarArchivo() {



    const fileError =

      document.querySelector(

        ".file-error"

      );



    if (fileError) {

      fileError.textContent = "";

    }



    if (

      !archivoPQRS.files ||

      archivoPQRS.files.length === 0

    ) {



      if (fileInfo) {

        fileInfo.textContent = "";

      }



      return true;

    }



    const file =

      archivoPQRS.files[0];



    const extension =

      file.name

        .split(".")

        .pop()

        .toLowerCase();



    if (

      !ALLOWED_EXTENSIONS.includes(

        extension

      )

    ) {



      if (fileError) {



        fileError.textContent =

          "Formato de archivo no permitido.";

      }



      return false;

    }



    if (

      file.size >

      MAX_FILE_SIZE

    ) {



      if (fileError) {



        fileError.textContent =

          "El archivo no puede superar los 8MB.";

      }



      return false;

    }



    if (fileInfo) {



      fileInfo.textContent =

        `Archivo adjunto: ${file.name}`;

    }



    return true;

  }



  /*

  ========================================================

  VALIDAR POLÍTICA

  ========================================================

  */



  function validarPolitica() {



    const checkboxError =

      document.querySelector(

        ".checkbox-error"

      );



    if (

      !politicaPQRS.checked

    ) {



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



  /*

  ========================================================

  MOSTRAR ERROR

  ========================================================

  */



  function mostrarError(

    campo,

    mensaje

  ) {



    if (!campo) return;



    const grupo =

      campo.closest(

        ".form-group"

      );



    if (!grupo) return;



    const error =

      grupo.querySelector(

        ".error-message"

      );



    if (error) {

      error.textContent =

        mensaje;

    }



    campo.setAttribute(

      "aria-invalid",

      "true"

    );

  }



  /*

  ========================================================

  LIMPIAR ERROR

  ========================================================

  */



  function limpiarError(

    campo

  ) {



    if (!campo) return;



    const grupo =

      campo.closest(

        ".form-group"

      );



    if (!grupo) return;



    const error =

      grupo.querySelector(

        ".error-message"

      );



    if (error) {

      error.textContent = "";

    }



    campo.removeAttribute(

      "aria-invalid"

    );

  }



  /*

  ========================================================

  LIMPIAR ERROR CHECKBOX

  ========================================================

  */



  function limpiarErrorCheckbox() {



    const checkboxError =

      document.querySelector(

        ".checkbox-error"

      );



    if (checkboxError) {

      checkboxError.textContent = "";

    }

  }



  /*

  ========================================================

  LIMPIAR ERRORES GENERALES

  ========================================================

  */



  function limpiarErroresGenerales() {



    form

      .querySelectorAll(

        ".error-message"

      )

      .forEach((error) => {



        error.textContent = "";

      });



    form

      .querySelectorAll(

        "input, textarea, select"

      )

      .forEach((campo) => {



        campo.removeAttribute(

          "aria-invalid"

        );

      });



    limpiarErrorCheckbox();

  }



  /*

  ========================================================

  MOSTRAR ESTADO

  ========================================================

  */



  function mostrarEstado(

    texto,

    tipo

  ) {



    if (!pqrsStatus) return;



    pqrsStatus.textContent =

      texto;



    pqrsStatus.classList.remove(

      "success",

      "error"

    );



    if (tipo) {



      pqrsStatus.classList.add(

        tipo

      );

    }

  }

});
