<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
?>

<div class="container py-4">
    <h2 class="text-white mb-4">Dashboard - Estadísticas por Mes</h2>
<!-- Selector de Mes -->
<form method="GET" action="/dashboard/admin" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <select name="mes" class="form-select" onchange="this.form.submit()">
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
