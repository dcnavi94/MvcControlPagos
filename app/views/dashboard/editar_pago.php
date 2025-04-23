<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<?php ob_start(); ?>

<h2>Editar Pago</h2>

<form method="POST" action="/dashboard/editarPago?id=<?= $pago['id'] ?>">
    <div class="mb-3">
        <label>Alumno:</label>
        <select name="user_id" class="form-select" required>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>" <?= $u['id'] == $pago['user_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Tipo:</label>
        <select name="type" class="form-select" required>
            <option value="colegiatura" <?= $pago['type'] === 'colegiatura' ? 'selected' : '' ?>>Colegiatura</option>
            <option value="reinscripcion" <?= $pago['type'] === 'reinscripcion' ? 'selected' : '' ?>>Reinscripción</option>
            <option value="recargo" <?= $pago['type'] === 'recargo' ? 'selected' : '' ?>>Recargo</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Monto:</label>
        <input type="number" step="0.01" name="amount" value="<?= $pago['amount'] ?>" class="form-control" required />
    </div>

    <div class="mb-3">
        <label>Estado:</label>
        <select name="status" class="form-select" required>
            <option value="pendiente" <?= $pago['status'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="pagado" <?= $pago['status'] === 'pagado' ? 'selected' : '' ?>>Pagado</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha de pago:</label>
        <input type="date" name="payment_date" value="<?= $pago['payment_date'] ?>" class="form-control" />
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="/dashboard/pagos" class="btn btn-secondary">Cancelar</a>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
