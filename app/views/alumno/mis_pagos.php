<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
?>

<div class="container py-4">
    <h2 class="text-white mb-4">Mis Pagos</h2>

    <!-- Mensaje de éxito o cancelación -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="alert alert-success">¡Pago realizado correctamente!</div>
    <?php elseif (isset($_GET['cancel']) && $_GET['cancel'] == '1'): ?>
        <div class="alert alert-danger">Pago cancelado.</div>
    <?php endif; ?>

    <?php if (empty($pagos)): ?>
        <div class="alert alert-info">No tienes pagos registrados en el sistema.</div>
    <?php else: ?>
        <table class="table table-hover table-dark table-bordered">
    <thead class="table-light">
        <tr>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagos as $pago): ?>
            <tr>
                <td><?= htmlspecialchars($pago['type']) ?></td>
                <td>$<?= number_format($pago['amount'], 2) ?></td>
                <td><?= $pago['payment_date'] ? date('d/m/Y', strtotime($pago['payment_date'])) : '-' ?></td>
                <td>
                    <?php if ($pago['status'] === 'pagado'): ?>
                        <span class="badge bg-success">Pagado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Pendiente</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($pago['status'] === 'pendiente'): ?>
                        <button class="btn btn-primary btn-sm pagar-btn" data-id="<?= $pago['id'] ?>" data-amount="<?= $pago['amount'] ?>">
                            Pagar ahora
                        </button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            
        </div>
    <?php endif; ?>
</div>

<!-- Script para manejar pago con redirección a PayPal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pagar-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pagoId = this.dataset.id;
            const amount = this.dataset.amount;

            fetch('/api/paypal/crear-orden.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ pago_id: pagoId, amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error al crear la orden de pago.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo iniciar el pago.');
            });
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/alumno_layout.php';
?>
