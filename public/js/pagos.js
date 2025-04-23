document.addEventListener('DOMContentLoaded', function () {
    const table = $('#tablaPagos').DataTable({
        pageLength: 15,
        lengthChange: false,
        dom: 'Bt<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'd-none',
                title: 'Pagos',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
            },
            {
                extend: 'pdfHtml5',
                className: 'd-none',
                title: 'Pagos',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [0, 1, 2, 3, 4] }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // Botones de exportación
    document.getElementById('btnExportarExcel')?.addEventListener('click', () => table.button(0).trigger());
    document.getElementById('btnExportarPDF')?.addEventListener('click', () => table.button(1).trigger());

    // Filtro personalizado
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        const tipoFiltro   = document.getElementById('typeFilter')?.value.trim();
        const estadoFiltro = document.getElementById('statusFilter')?.value.trim();
        const fechaFiltro  = document.getElementById('fechaFilter')?.value.trim(); // yyyy-mm
        const buscar       = document.getElementById('searchInput')?.value.trim().toLowerCase();

        const nombreAlumno = data[0]?.toLowerCase() || '';
        const tipoPago     = data[1]?.trim() || '';
        const estado       = data[3]?.trim() || '';

        // Obtenemos la fecha cruda del atributo data-fecha
        const row = table.row(dataIndex).node();
        const fechaRaw = row.querySelector('td[data-fecha]')?.dataset.fecha || ''; // yyyy-mm-dd

        const coincideAlumno = !buscar || nombreAlumno.includes(buscar);
        const coincideTipo   = !tipoFiltro || tipoPago === tipoFiltro;
        const coincideEstado = !estadoFiltro || estado === estadoFiltro;

        let coincideFecha = true;
        if (fechaFiltro && fechaRaw) {
            const [filtroAño, filtroMes] = fechaFiltro.split('-');
            const [año, mes] = fechaRaw.split('-');
            coincideFecha = filtroAño === año && filtroMes === mes;
        }

        return coincideAlumno && coincideTipo && coincideEstado && coincideFecha;
    });

    // Eventos para aplicar filtros
    ['typeFilter', 'statusFilter', 'fechaFilter'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => table.draw());
    });

    document.getElementById('searchInput')?.addEventListener('keyup', () => table.draw());
});
