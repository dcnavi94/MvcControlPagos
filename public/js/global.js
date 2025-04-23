// Mostrar una alerta Bootstrap dinámica
function mostrarAlerta(tipo, mensaje, destino = '#alert-container') {
    const alert = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `;
    const contenedor = document.querySelector(destino);
    if (contenedor) contenedor.innerHTML = alert;
}

// Confirmación al eliminar (puedes reutilizar en cualquier tabla)
function confirmarEliminacion(mensaje = '¿Estás seguro de que deseas eliminar este registro?') {
    return confirm(mensaje);
}

// Mostrar loader (puedes vincularlo a tus botones de submit)
function mostrarLoader(idContenedor = 'body') {
    const loaderHTML = `
        <div id="loader-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;">
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
        </div>`;
    document.querySelector(idContenedor).insertAdjacentHTML('beforeend', loaderHTML);
}

function ocultarLoader() {
    const loader = document.getElementById('loader-overlay');
    if (loader) loader.remove();
}

// Copiar texto al portapapeles
function copiarAlPortapapeles(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        mostrarAlerta('success', 'Texto copiado al portapapeles');
    }).catch(() => {
        mostrarAlerta('danger', 'No se pudo copiar el texto');
    });
}

// Mostrar toast (usando Bootstrap 5)
function mostrarToast(mensaje, tipo = 'success') {
    const toastID = `toast-${Date.now()}`;
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${tipo} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.id = toastID;

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
}

// Mostrar alerta Bootstrap
function mostrarAlerta(tipo, mensaje, destino = '#alert-container') {
    const alert = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `;
    const contenedor = document.querySelector(destino);
    if (contenedor) contenedor.innerHTML = alert;
}

// Confirmar eliminación
function confirmarEliminacion(mensaje = '¿Estás seguro que deseas eliminar este registro?') {
    return confirm(mensaje);
}

// Mostrar loader
function mostrarLoader(idContenedor = 'body') {
    const loaderHTML = `
        <div id="loader-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;">
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
        </div>`;
    document.querySelector(idContenedor).insertAdjacentHTML('beforeend', loaderHTML);
}
function ocultarLoader() {
    const loader = document.getElementById('loader-overlay');
    if (loader) loader.remove();
}

// Copiar texto
function copiarAlPortapapeles(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        mostrarToast('Texto copiado al portapapeles');
    }).catch(() => {
        mostrarToast('No se pudo copiar el texto', 'danger');
    });
}

// Toast Bootstrap 5
function mostrarToast(mensaje, tipo = 'success') {
    const toastID = `toast-${Date.now()}`;
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${tipo} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.id = toastID;
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
}

// ✅ Validación básica de formulario
function validarFormulario(campos) {
    for (let campo of campos) {
        const valor = campo.value.trim();
        if (!valor) {
            mostrarAlerta('warning', `El campo "${campo.name}" no puede estar vacío.`);
            campo.focus();
            return false;
        }
        if (campo.type === 'email' && !/^\S+@\S+\.\S+$/.test(valor)) {
            mostrarAlerta('warning', `El email "${valor}" no es válido.`);
            campo.focus();
            return false;
        }
    }
    return true;
}

// ✅ Envío POST con fetch
async function postData(url, data, successMsg = '', errorMsg = '') {
    try {
        mostrarLoader();
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const json = await response.json();
        ocultarLoader();

        if (response.ok && json.success) {
            mostrarToast(successMsg || json.message || 'Guardado correctamente');
        } else {
            mostrarToast(errorMsg || json.message || 'Ocurrió un error', 'danger');
        }

        return json;
    } catch (err) {
        ocultarLoader();
        console.error(err);
        mostrarToast(errorMsg || 'Error de red o servidor', 'danger');
        return { success: false };
    }
}

// ✅ Obtener datos por GET
async function getData(url) {
    try {
        mostrarLoader();
        const response = await fetch(url);
        const data = await response.json();
        ocultarLoader();
        return data;
    } catch (err) {
        ocultarLoader();
        mostrarToast('Error al obtener datos', 'danger');
        return null;
    }
}


