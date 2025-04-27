<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;

ob_start();
?>
<br>
<h2>Mi Perfil</h2>

<?php if (!$user): ?>
    <div class="alert alert-danger">No se encontró información del usuario.</div>
<?php else: ?>
    <div id="alert-container"></div>

    <form action="/alumno/guardarPerfil" method="POST" data-validate data-ajax="true" data-reset="false">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required data-email value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña (opcional)</label>
            <input type="password" name="password" id="password" class="form-control" data-minlength="6">
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/alumno_layout.php';
?>
