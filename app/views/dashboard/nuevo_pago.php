<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<?php ob_start(); ?>

<h2>Registrar Nuevo Pago</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="/dashboard/nuevoPago">
    <div class="mb-3">
        <label for="user_id" class="form-label">Alumno:</label>
        <select name="user_id" id="user_id" class="form-select" required>
            <option value="">Seleccione un alumno</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">Tipo de Pago:</label>
        <select name="type" id="type" class="form-select" required>
            <option value="colegiatura">Colegiatura</option>
            <option value="reinscripcion">Reinscripción</option>
            <option value="recargo">Recargo</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">Monto:</label>
        <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Estado:</label>
        <select name="status" id="status" class="form-select" required>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="payment_date" class="form-label">Fecha de Pago (opcional):</label>
        <input type="date" name="payment_date" id="payment_date" class="form-control">
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success">Guardar Pago</button>
        <a href="/dashboard/pagos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>


