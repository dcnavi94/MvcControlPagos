<?php
ob_start(); // Captura el contenido del buffer
?>
<h2>Panel de Administrador</h2>

<ul class="list-group">
    <li class="list-group-item"><a href="/dashboard/usuarios">Gestión de Usuarios</a></li>
    <li class="list-group-item"><a href="/dashboard/pagos">Gestión de Pagos</a></li>
    <li class="list-group-item"><a href="/dashboard/crearUsuario">Crear Nuevo Usuario</a></li>
</ul>
<a href="/auth/logout" class="btn btn-danger mt-4">Cerrar sesión</a>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin_layout.php';
?>

 

