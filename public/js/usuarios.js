


document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('tablaUsuarios');
    if (!tabla) return;

    // Si ya existe una instancia de DataTable, destrúyela
    if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
        $('#tablaUsuarios').DataTable().clear().destroy();
    }

    const table = $('#tablaUsuarios').DataTable({
        pageLength: 15,
        lengthChange: false,
        dom: 'Bt<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'd-none', // Ocultar botón nativo
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

    // Botones personalizados para exportar
    document.getElementById('btnExportarExcel')?.addEventListener('click', () => table.button(0).trigger());
    document.getElementById('btnExportarPDF')?.addEventListener('click', () => table.button(1).trigger());

    // Filtros personalizados
    function aplicarFiltros() {
        const role = document.getElementById('roleFilter')?.value || '';
        const career = document.getElementById('careerFilter')?.value || '';
        const group = document.getElementById('groupFilter')?.value || '';
        const search = document.getElementById('searchInput')?.value || '';
    
        table.column(3).search(role);
        table.column(4).search(career);
        table.column(6).search(group);  // <- Filtro de grupo por nombre (ej. 2501)
        table.search(search).draw();
    }
    

    // Eventos para los filtros
    ['roleFilter', 'careerFilter', 'groupFilter'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', aplicarFiltros);
    });

    document.getElementById('searchInput')?.addEventListener('keyup', aplicarFiltros);
});

