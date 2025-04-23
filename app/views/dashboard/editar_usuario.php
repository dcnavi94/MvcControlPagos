<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../models/Career.php';
require_once __DIR__ . '/../../models/Grade.php';
require_once __DIR__ . '/../../models/Grupo.php';

$careers = Career::all();
$grades = Grade::all();
$grupos = Grupo::all();
?>

<?php ob_start(); ?>

<h2>Editar Usuario</h2>

<form method="POST" action="/dashboard/editarUsuario?id=<?= $usuario['id'] ?>">
    <div class="mb-3">
        <label>Nombre:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($usuario['name']) ?>" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Rol:</label>
        <select name="role" id="role" class="form-select" onchange="toggleAlumnoFields()">
            <option value="alumno" <?= $usuario['role'] === 'alumno' ? 'selected' : '' ?>>Alumno</option>
            <option value="admin" <?= $usuario['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
    </div>

    <div id="alumnoFields" style="display: none;">
        <div class="mb-3">
            <label for="career_id" class="form-label">Carrera:</label>
            <select name="career_id" id="career_id" class="form-select">
                <option value="">Seleccione una carrera</option>
                <?php foreach ($careers as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $usuario['career_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="grade_id" class="form-label">Grado:</label>
            <select name="grade_id" id="grade_id" class="form-select">
                <option value="">Seleccione un grado</option>
                <?php foreach ($grades as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= $usuario['grade_id'] == $g['id'] ? 'selected' : '' ?>><?= htmlspecialchars($g['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="group_id" class="form-label">Grupo:</label>
            <select name="group_id" id="group_id" class="form-select">
                <option value="">Seleccione un grupo</option>
                <?php foreach ($grupos as $gr): ?>
                    <option value="<?= $gr['id'] ?>" <?= $usuario['group_id'] == $gr['id'] ? 'selected' : '' ?>><?= htmlspecialchars($gr['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mb-3">
    <label for="password" class="form-label">Contraseña (dejar en blanco si no se cambia):</label>
    <input type="password" name="password" id="password" class="form-control" />
</div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="/dashboard/usuarios" class="btn btn-secondary">Cancelar</a>
</form>

<script>
    function toggleAlumnoFields() {
        const role = document.getElementById('role').value;
        document.getElementById('alumnoFields').style.display = role === 'alumno' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', toggleAlumnoFields);
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
