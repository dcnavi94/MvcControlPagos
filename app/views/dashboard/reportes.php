<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
?>

<div class="container py-4">
    <h2 class="mb-4 text-center">📄 Generador de Reportes</h2>

    <!-- Formulario -->
    <form method="GET" action="/dashboard/generar-reporte" target="_blank" class="row g-3 p-3 rounded shadow">

        <!-- Filtro de Mes -->
        <div class="col-md-4">
            <label for="mes" class="form-label">Mes</label>
            <select name="mes" id="mes" class="form-select" required>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <?php $monthName = (new DateTime("2025-$i-01"))->format('F'); ?>
                    <option value="<?= $i ?>" <?= (date('n') == $i) ? 'selected' : '' ?>>
                        <?= $monthName ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Filtro de Método de Pago -->
        <div class="col-md-4">
            <label for="metodopago" class="form-label">Método de Pago</label>
            <select name="metodopago" id="metodopago" class="form-select">
                <option value="">Todos</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>

        <!-- Filtro de Grupos -->
        <div class="col-md-4">
            <label for="grupos" class="form-label">Seleccionar Grupos</label>
            <select name="grupos[]" id="grupos" class="form-select" multiple="multiple" required>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>"><?= htmlspecialchars($grupo['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Puedes seleccionar múltiples grupos.</small>
        </div>

        <!-- Botón -->
        <div class="col-12 text-end mt-4">
            <button type="submit" class="btn btn-primary">
                📄 Generar Reporte PDF
            </button>
        </div>
    </form>
</div>

<!-- CDN de Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Activar Select2 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#grupos').select2({
        placeholder: "Seleccionar uno o varios grupos...",
        allowClear: true,
        width: '100%'
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>

