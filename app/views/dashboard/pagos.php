<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
ob_start();
?>

<br>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Pagos Registrados</h2>
    <div>
        <a href="/dashboard/nuevoPago" class="btn btn-primary me-2">Registrar Pago</a>
        <button id="btnExportarExcel" class="btn btn-success me-2">Exportar Excel</button>
        <button id="btnExportarPDF" class="btn btn-danger">Exportar PDF</button>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<!-- Filtros -->
<div class="row mb-3">
    <div class="col-md-3">
        <label for="typeFilter" class="form-label">tipo:</label>
        <select id="typeFilter" class="form-select">
            <option value="">todos</option>
            <option value="colegiatura">colegiatura</option>
            <option value="reinscripcion">reinscripcion</option>
            <option value="recargo">recargo</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="statusFilter" class="form-label">estado:</label>
        <select id="statusFilter" class="form-select">
            <option value="">todos</option>
            <option value="pendiente">pendiente</option>
            <option value="pagado">pagado</option>
        </select>
    </div>
    <div class="col-md-3">
    <label for="fechaFilter" class="form-label">Fecha (MM/AAAA):</label>
    <input type="month" id="fechaFilter" class="form-control">



</div>


    <div class="col-md-3">
        <label for="searchInput" class="form-label">buscar alumno:</label>
        <input type="text" id="searchInput" class="form-control" placeholder="nombre del alumno">
    </div>
</div>

<table id="tablaPagos" class="table table-bordered table-striped mt-3">
    <thead class="table-light">
        <tr>
            <th>Alumno</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Fecha de pago</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagos as $pago): ?>
            <tr>
                <td><?= htmlspecialchars($pago['name']) ?></td>
                <td><?= $pago['type'] ?></td>
               
                <td>$<?= number_format($pago['amount'], 2) ?></td>
                <td><?= $pago['status'] ?></td>
                <td>
    <?php
        if (!empty($pago['payment_date'])) {
            $formatter = new IntlDateFormatter(
                'es_ES',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE,
                null,
                null,
                "MMMM 'de' yyyy"
            );
            $fecha = new DateTime($pago['payment_date']);
            echo mb_strtolower($formatter->format($fecha), 'UTF-8');
        } else {
            echo '-';
        }
    ?>
</td>




                <td>
                    <a href="/dashboard/editarPago?id=<?= $pago['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="/dashboard/eliminarPago?id=<?= $pago['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este pago?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
