<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
?>

<h2 class="mb-4">Generador de Reportes</h2>

<!-- Formulario de generación -->
<form method="POST" action="/dashboard/generarReporte.php" class="row g-3">
    <div class="col-md-4">
        <label for="tipoReporte" class="form-label">Tipo de Reporte</label>
        <select name="tipoReporte" id="tipoReporte" class="form-select" required>
            <option value="usuarios">Usuarios Registrados</option>
            <option value="pagos">Pagos Realizados</option>
            <option value="ingresos">Ingresos por Carrera</option>
        </select>
    </div>

    <div class="col-md-3">
        <label for="desde" class="form-label">Desde</label>
        <input type="date" name="desde" id="desde" class="form-control">
    </div>

    <div class="col-md-3">
        <label for="hasta" class="form-label">Hasta</label>
        <input type="date" name="hasta" id="hasta" class="form-control">
    </div>

    <div class="col-md-3">
        <label for="formato" class="form-label">Exportar Como</label>
        <select name="formato" id="formato" class="form-select" required>
            <option value="excel">Excel</option>
            <option value="pdf">PDF</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </div>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>


