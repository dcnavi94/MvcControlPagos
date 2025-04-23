<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
ob_start();
?>
<br>
<!-- Encabezado con botones -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Usuarios Registrados</h2>
    <div>
        <a href="/dashboard/crearusuario" class="btn btn-primary me-2">Crear Usuario</a>
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
        <label for="roleFilter" class="form-label">Filtrar por Rol:</label>
        <select id="roleFilter" class="form-select">
            <option value="">Todos</option>
            <option value="admin">Admin</option>
            <option value="alumno">Alumno</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="careerFilter" class="form-label">Filtrar por Carrera:</label>
        <select id="careerFilter" class="form-select">
    <option value="">Todas</option>
    <?php foreach ($careers as $c): ?>
        <option value="<?= htmlspecialchars($c['name']) ?>"><?= htmlspecialchars($c['name']) ?></option>
    <?php endforeach; ?>
</select>

    </div>

    <div class="col-md-3">
    <label for="groupFilter" class="form-label">Filtrar por grupo:</label>
    <select id="groupFilter" class="form-select">
    <option value="">Todos</option>
    <?php foreach ($grupos as $g): ?>
        <option value="<?= htmlspecialchars($g['name']) ?>"><?= htmlspecialchars($g['name']) ?></option>
    <?php endforeach; ?>
</select>
</div>


    <div class="col-md-3">
        <label for="searchInput" class="form-label">Buscar usuario:</label>
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o email">
    </div>
</div>
<?php
$gruposUnicos = array_unique(array_filter(array_column($usuarios, 'group_name')));
sort($gruposUnicos);
?>

<!-- Tabla -->
<table id="tablaUsuarios" class="table table-bordered table-striped mt-3">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Carrera</th>
            <th>Grado</th>
            <th>Grupo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td><?= $u['career'] ?? '-' ?></td>
                <td><?= $u['grade'] ?? '-' ?></td>
                <td><?= $u['group_name'] ?? '-' ?></td>
                <td>
                    <a href="/dashboard/editarUsuario?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="/dashboard/eliminarUsuario?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>




<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>



