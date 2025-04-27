<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
?>

<div class="container py-4">
    <h2 class="text-white mb-4">Dashboard - Estadísticas por Mes</h2>
<!-- Filtro de Mes y Grupo -->
<form method="GET" action="/dashboard/admin" class="mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <label for="mes" class="form-label text-white">Mes</label>
            <select name="mes" id="mes" class="form-select" onchange="this.form.submit()">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <?php
                    $monthName = (new DateTime("2025-$i-01"))->format('F');
                    ?>
                    <option value="<?= $i ?>" <?= (isset($_GET['mes']) && $_GET['mes'] == $i) || (!isset($_GET['mes']) && date('n') == $i) ? 'selected' : '' ?>>
                        <?= $monthName ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="grupo" class="form-label text-white">Grupos</label>
            <select name="grupos[]" id="grupo" class="form-select" multiple onchange="this.form.submit()">
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>" <?= (isset($_GET['grupos']) && in_array($grupo['id'], (array)$_GET['grupos'])) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grupo['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="text-white">Mantén Ctrl (o Cmd) para seleccionar varios.</small>
        </div>
    </div>
</form>



    <div class="row">
        <!-- Total Pagado -->
        <div class="col-md-6 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Monto Total Pagado</h5>
                    <p class="card-text fs-2">$<?= number_format($montoPagadoMes, 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Total Pendiente -->
        <div class="col-md-6 mb-4">
            <div class="card text-dark bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Monto Total Pendiente</h5>
                    <p class="card-text fs-2">$<?= number_format($montoPendienteMes, 2) ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
