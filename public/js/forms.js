document.addEventListener('DOMContentLoaded', () => {
    // Todos los formularios con data-validate
    const formularios = document.querySelectorAll('form[data-validate]');

    formularios.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const inputs = form.querySelectorAll('[required], [data-email], [data-minlength]');
            let valid = true;

            for (const input of inputs) {
                const valor = input.value.trim();

                if (input.hasAttribute('required') && !valor) {
                    mostrarAlerta('warning', `El campo "${input.name}" es obligatorio.`);
                    input.focus();
                    valid = false;
                    break;
                }

                if (input.hasAttribute('data-email') && !/^\S+@\S+\.\S+$/.test(valor)) {
                    mostrarAlerta('warning', `El email "${valor}" no es válido.`);
                    input.focus();
                    valid = false;
                    break;
                }

                if (input.hasAttribute('data-minlength')) {
                    const min = parseInt(input.dataset.minlength);
                    if (valor.length < min) {
                        mostrarAlerta('warning', `El campo "${input.name}" debe tener al menos ${min} caracteres.`);
                        input.focus();
                        valid = false;
                        break;
                    }
                }
            }

            if (!valid) return;

            // Si tiene data-ajax, envía con fetch
            if (form.dataset.ajax === "true") {
                const data = {};
                new FormData(form).forEach((v, k) => data[k] = v);

                const action = form.action || window.location.href;
                const method = form.method.toUpperCase() || 'POST';

                const response = await postData(action, data, 'Formulario enviado correctamente', 'Error al enviar');

                if (response.success && form.dataset.reset === "true") {
                    form.reset();
                }

                if (form.dataset.redirect) {
                    setTimeout(() => window.location.href = form.dataset.redirect, 1000);
                }
            } else {
                form.submit(); // Envío tradicional
            }
        });
    });
});

