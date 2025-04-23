<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

ob_start();
?>

<div class="row layout-top-spacing">
    <div class="col-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12">
                        <br>
                        <h4>Crear Nuevo Usuario</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">

                <?php if ($message): ?>
                    <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="/dashboard/crearUsuario" method="POST" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        <input type="text" name="name" id="name" class="form-control" required />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" name="email" id="email" class="form-control" required />
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rol:</label>
                        <select name="role" id="role" class="form-select" required onchange="toggleAlumnoFields()">
                            <option value="">Seleccione un rol</option>
                            <option value="alumno">Alumno</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <!-- Solo visible si se selecciona 'alumno' -->
                    <div id="alumnoFields" style="display: none;">
                        <div class="mb-3">
                            <label for="career_id" class="form-label">Carrera:</label>
                            <select name="career_id" id="career_id" class="form-select">
                                <option value="">Seleccione una carrera</option>
                                <?php foreach ($careers as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="grade_id" class="form-label">Grado:</label>
                            <select name="grade_id" id="grade_id" class="form-select">
                                <option value="">Seleccione un grado</option>
                                <?php foreach ($grades as $g): ?>
                                    <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="group_id" class="form-label">Grupo:</label>
                            <select name="group_id" id="group_id" class="form-select">
                                <option value="">Seleccione un grupo</option>
                                <?php foreach ($grupos as $gr): ?>
                                    <option value="<?= $gr['id'] ?>"><?= htmlspecialchars($gr['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Contraseña automática para alumnos -->
                        <input type="hidden" name="password" value="Unives12345">
                    </div>

                    <!-- Solo visible si se selecciona 'admin' -->
                    <div id="adminPasswordField" style="display: none;">
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" id="password" class="form-control" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                        <a href="/dashboard/usuarios" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

                <script>
                    function toggleAlumnoFields() {
                        const role = document.getElementById('role').value;
                        document.getElementById('alumnoFields').style.display = role === 'alumno' ? 'block' : 'none';
                        document.getElementById('adminPasswordField').style.display = role === 'admin' ? 'block' : 'none';
                    }

                    document.addEventListener('DOMContentLoaded', toggleAlumnoFields);
                </script>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>
