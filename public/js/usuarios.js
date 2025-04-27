document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('tablaUsuarios');
    if (!tabla) return;

    console.log("usuarios.js cargado ✅");

    // Destruir instancia previa si existe
    if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
        $('#tablaUsuarios').DataTable().clear().destroy();
    }

    // Inicialización de DataTable
    const table = $('#tablaUsuarios').DataTable({
        pageLength: 15,
        lengthChange: false,
        dom: 'Bt<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'd-none',
                title: 'Usuarios',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
            },
            {
                extend: 'pdfHtml5',
                className: 'd-none',
                title: 'Usuarios',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // Exportación personalizada
    document.getElementById('btnExportarExcel')?.addEventListener('click', () => {
        table.button(0).trigger();
    });

    document.getElementById('btnExportarPDF')?.addEventListener('click', () => {
        table.button(1).trigger();
    });

    // Función de filtrado personalizada
    function aplicarFiltros() {
        const role = document.getElementById('roleFilter')?.value.trim() || '';
        const career = document.getElementById('careerFilter')?.value.trim() || '';
        const group = document.getElementById('groupFilter')?.value.trim() || '';
        const search = document.getElementById('searchInput')?.value.trim() || '';

        // Aplicar búsquedas exactas por columna (usando regex)
        table.column(3).search(role ? `^${role}$` : '', true, false);   // Rol
        table.column(4).search(career ? `^${career}$` : '', true, false); // Carrera
        table.column(6).search(group ? `^${group}$` : '', true, false);  // Grupo

        // Búsqueda global (nombre o email)
        table.search(search).draw();
    }

    // Listeners para filtros
    ['roleFilter', 'careerFilter', 'groupFilter'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', () => {
                console.log(`Filtro ${id} aplicado`);
                aplicarFiltros();
            });
        }
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', aplicarFiltros);
    }
});
