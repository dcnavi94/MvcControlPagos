<?php
// Ajustar la ruta a la base de datos
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php'; // Ruta a tu carpeta dompdf
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/PagoModel.php'; // 👈 OBLIGATORIO: antes de usar PagoModel

use Dompdf\Dompdf;
use Dompdf\Options;




use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class DashboardController
{
    public function index() {
        session_start();
        require_once __DIR__ . '/../models/PagoModel.php';
        require_once __DIR__ . '/../models/GrupoModel.php'; // Para traer grupos disponibles
    
        $pagoModel = new PagoModel();
        $grupoModel = new GrupoModel();
    
        $mesSeleccionado = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
        $gruposSeleccionados = isset($_GET['grupos']) ? array_map('intval', (array)$_GET['grupos']) : [];
    
        $montoPagadoMes = $pagoModel->sumarPagosPorEstadoYMES('pagado', $mesSeleccionado, $gruposSeleccionados);
        $montoPendienteMes = $pagoModel->sumarPagosPorEstadoYMES('pendiente', $mesSeleccionado, $gruposSeleccionados);
    
        $grupos = $grupoModel->obtenerTodos();
    
        require_once __DIR__ . '/../views/dashboard/admin.php';
    }
    

    public function admin()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../views/dashboard/admin.php';
    }

    public function crearUsuario()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../models/Career.php';
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/Grupo.php';

        $careers = Career::all();
        $grades  = Grade::all();
        $grupos  = Grupo::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;

            $name  = trim($_POST['name']);
            $email = trim($_POST['email']);
            $role  = $_POST['role'];

            $password = ($role === 'alumno') ? 'Unives12345' : trim($_POST['password']);
            $hashed   = password_hash($password, PASSWORD_DEFAULT);

            $career_id = !empty($_POST['career_id']) ? intval($_POST['career_id']) : null;
            $grade_id  = !empty($_POST['grade_id']) ? intval($_POST['grade_id']) : null;
            $group_id  = !empty($_POST['group_id']) ? intval($_POST['group_id']) : null;

            if ($name && $email && $password && in_array($role, ['admin', 'alumno'])) {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, career_id, grade_id, group_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashed, $role, $career_id, $grade_id, $group_id]);

                $_SESSION['message'] = "✅ Usuario creado correctamente.";
                header('Location: /dashboard/crearUsuario');
                exit;
            } else {
                $_SESSION['error'] = "❌ Datos inválidos o incompletos.";
            }
        }

        require __DIR__ . '/../views/dashboard/crear_usuario.php';
    }

    public function usuarios()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }
    
        global $pdo;
    
        $stmt = $pdo->query("
            SELECT u.*, 
                   c.name AS career, 
                   g.name AS grade, 
                   gr.name AS group_name
            FROM users u
            LEFT JOIN careers c ON u.career_id = c.id
            LEFT JOIN grades g ON u.grade_id = g.id
            LEFT JOIN grupo gr ON u.group_id = gr.id
            ORDER BY u.created_at DESC
        ");
    
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grupos = $pdo->query("SELECT id, name FROM grupo ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        $grades = $pdo->query("SELECT id, name FROM grades ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        $careers = $pdo->query("SELECT id, name FROM careers ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    
        require_once __DIR__ . '/../views/dashboard/usuarios.php';
       

    }
    
    public function editarUsuario()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }
    
        global $pdo;
    
        $id = $_GET['id'] ?? null;
    
        // Cargar usuario actual
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        require_once __DIR__ . '/../models/Career.php';
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/Grupo.php';
    
        $careers = Career::all();
        $grades  = Grade::all();
        $grupos  = Grupo::all();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name']);
            $email = trim($_POST['email']);
            $role  = $_POST['role'];
            $password = trim($_POST['password'] ?? '');
    
            $career_id = $role === 'alumno' ? ($_POST['career_id'] ?? null) : null;
            $grade_id  = $role === 'alumno' ? ($_POST['grade_id'] ?? null) : null;
            $group_id  = $role === 'alumno' ? ($_POST['group_id'] ?? null) : null;
    
            $sql = "UPDATE users SET name = ?, email = ?, role = ?, career_id = ?, grade_id = ?, group_id = ?";
            $params = [$name, $email, $role, $career_id, $grade_id, $group_id];
    
            // Solo actualizar contraseña si se envía
            if (!empty($password)) {
                $sql .= ", password = ?";
                $params[] = password_hash($password, PASSWORD_DEFAULT);
            }
    
            $sql .= " WHERE id = ?";
            $params[] = $id;
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
    
            $_SESSION['message'] = "✅ Usuario actualizado correctamente.";
            header('Location: /dashboard/usuarios');
            exit;
        }
    
        require __DIR__ . '/../views/dashboard/editar_usuario.php';
    }
    
    

    public function eliminarUsuario()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }

        global $pdo;
        $id = $_GET['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        }

        $_SESSION['message'] = "🗑️ Usuario eliminado.";
        header('Location: /dashboard/usuarios');
        exit;
    }

    public function pagos()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }

        global $pdo;
        $stmt = $pdo->query("SELECT payments.*, users.name FROM payments JOIN users ON payments.user_id = users.id ORDER BY payment_date DESC");
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/dashboard/pagos.php';
    }

    public function nuevoPago()
{
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /auth/login');
        exit;
    }

    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        $type = $_POST['type'];
        $amount = $_POST['amount'];
        $status = $_POST['status'];
        $payment_date = $_POST['payment_date'] ?? null;

        // Validaciones simples
        if (!$user_id || !$type || !$amount || !$status) {
            $_SESSION['error'] = "❌ Todos los campos obligatorios deben estar completos.";
            header('Location: /dashboard/nuevoPago');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO payments (user_id, type, amount, status, payment_date) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $type, $amount, $status, $payment_date]);

        $_SESSION['message'] = "✅ Pago registrado correctamente.";
        header('Location: /dashboard/pagos');
        exit;
    }

    $usuarios = $pdo->query("SELECT id, name FROM users ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    require_once __DIR__ . '/../views/dashboard/nuevo_pago.php';
}


public function editarPago()
{
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /auth/login');
        exit;
    }

    global $pdo;
    $id = $_GET['id'] ?? null;

    if (!$id) {
        $_SESSION['error'] = "ID de pago no válido.";
        header('Location: /dashboard/pagos');
        exit;
    }

    // Si envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        $type = $_POST['type'];
        $amount = $_POST['amount'];
        $status = $_POST['status'];
        $payment_date = $_POST['payment_date'] ?? null;

        try {
            $stmt = $pdo->prepare("
                UPDATE payments 
                SET user_id = ?, type = ?, amount = ?, status = ?, payment_date = ? 
                WHERE id = ?
            ");
            $stmt->execute([$user_id, $type, $amount, $status, $payment_date, $id]);

            $_SESSION['message'] = "✅ Pago actualizado correctamente.";
            header('Location: /dashboard/pagos');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "❌ Error al actualizar el pago: " . $e->getMessage();
        }
    }

    // Obtener datos actuales del pago
    $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->execute([$id]);
    $pago = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener usuarios
    $usuarios = $pdo->query("SELECT id, name FROM users ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

    require_once __DIR__ . '/../views/dashboard/editar_pago.php';
}


    public function eliminarPago()
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }

        global $pdo;
        $id = $_GET['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
            $stmt->execute([$id]);
        }

        $_SESSION['message'] = "🗑️ Pago eliminado.";
        header('Location: /dashboard/pagos');
        exit;
    }

    public function perfil()
    {
        require_once __DIR__ . '/../views/dashboard/perfil.php';
    }

    public function guardarPerfil()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        global $pdo;
        $id = $_SESSION['user']['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password'] ?? '');

        if ($name === '' || $email === '') {
            echo json_encode(['success' => false, 'message' => 'Nombre y correo son obligatorios']);
            return;
        }

        try {
            $sql = "UPDATE users SET name = ?, email = ?" . ($password !== '' ? ", password = ?" : "") . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);

            $params = [$name, $email];
            if ($password !== '') {
                $params[] = password_hash($password, PASSWORD_BCRYPT);
            }
            $params[] = $id;

            $stmt->execute($params);

            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;

            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }
    
    public function reportes()
{
    session_start();
    require_once __DIR__ . '/../models/GrupoModel.php';

    $grupoModel = new GrupoModel();
    $grupos = $grupoModel->obtenerTodos(); // <-- ¡Esto es obligatorio!

    require_once __DIR__ . '/../views/dashboard/reportes.php';
}
    
public function generarReporte()
{
    session_start();
    global $pdo;


    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
    $anio = date('Y');
    $metodopago = isset($_GET['metodopago']) ? trim($_GET['metodopago']) : '';
    $gruposSeleccionados = isset($_GET['grupos']) ? array_map('intval', (array)$_GET['grupos']) : [];

    $params = [$mes, $anio];

    $sql = "
        SELECT 
            p.amount,
            p.payment_date,
            p.status,
            p.type,
            p.metodopago,
            u.name AS alumno,
            g.name AS grupo
        FROM payments p
        INNER JOIN users u ON p.user_id = u.id
        LEFT JOIN grupo g ON u.group_id = g.id
        WHERE MONTH(p.payment_date) = ?
          AND YEAR(p.payment_date) = ?
    ";

    if (!empty($metodopago)) {
        $sql .= " AND p.metodopago = ?";
        $params[] = $metodopago;
    }

    if (!empty($gruposSeleccionados)) {
        $placeholders = implode(',', array_fill(0, count($gruposSeleccionados), '?'));
        $sql .= " AND u.group_id IN ($placeholders)";
        $params = array_merge($params, $gruposSeleccionados);
    }

    $sql .= " ORDER BY g.name, p.payment_date";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($pagos)) {
        echo "<h2 style='color:red; text-align:center;'>⚠️ No se encontraron pagos para los filtros seleccionados.</h2>";
        exit;
    }

    // --- Procesar datos ---
    $pagosAntes7 = [];
    $pagosDespues7 = [];
    $totalAntes7 = 0;
    $totalDespues7 = 0;
    $pagosPorEstado = ['pagado' => 0, 'pendiente' => 0];
    $pagosPorTipoPagado = [];
    $pagosPorTipoPendiente = [];
    $pagosPorMetodo = [];

    foreach ($pagos as $pago) {
        $diaPago = (int)date('d', strtotime($pago['payment_date']));

        if ($diaPago <= 7) {
            $pagosAntes7[] = $pago;
            $totalAntes7 += $pago['amount'];
        } else {
            $pagosDespues7[] = $pago;
            $totalDespues7 += $pago['amount'];
        }

        $estado = $pago['status'];
        $pagosPorEstado[$estado] += $pago['amount'];

        if (!empty($pago['type'])) {
            if ($estado === 'pagado') {
                $pagosPorTipoPagado[$pago['type']] = ($pagosPorTipoPagado[$pago['type']] ?? 0) + $pago['amount'];
            } else {
                $pagosPorTipoPendiente[$pago['type']] = ($pagosPorTipoPendiente[$pago['type']] ?? 0) + $pago['amount'];
            }
        }

        if (!empty($pago['metodopago'])) {
            $pagosPorMetodo[$pago['metodopago']] = ($pagosPorMetodo[$pago['metodopago']] ?? 0) + $pago['amount'];
        }
    }

    $fecha = DateTime::createFromFormat('!m', $mes);
    $nombreMes = $fecha->format('F');

    ob_start();
?>
<style>
    body { font-family: Arial, sans-serif; }
    h1, h2, h3 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; }
    th, td { border: 1px solid #000; padding: 5px; font-size: 12px; }
    thead { background: #003366; color: white; }
    .pendiente { color: #f39c12; font-weight: bold; }
</style>

<div style="text-align: center; margin-bottom: 20px;">
    <img src="http://localhost/images/logo_unives.png" alt="Logo" style="width:80px;">
    <h1>Ciencias Artes y Metaeducación San José</h1>
    <h2>Reporte del Mes de <?= htmlspecialchars($nombreMes . ' ' . $anio) ?></h2>
</div>

<p style="text-align: center;">Fecha de generación: <?= date('d/m/Y') ?></p>

<h3>Pagos del 1 al 7</h3>
<?= $this->tablaPagos($pagosAntes7, $totalAntes7) ?>

<h3>Pagos del 8 en adelante</h3>
<?= $this->tablaPagos($pagosDespues7, $totalDespues7) ?>

<h2 style="text-align:center;">💵 Gran Total: $<?= number_format($totalAntes7 + $totalDespues7, 2) ?></h2>

<div style="page-break-before: always;"></div>

<h2>Resumen de Estado de Pagos</h2>

<table>
    <thead>
        <tr><th>Estado</th><th>Total</th></tr>
    </thead>
    <tbody>
        <tr><td>Pagado</td><td>$<?= number_format($pagosPorEstado['pagado'], 2) ?></td></tr>
        <tr><td class="pendiente">Pendiente</td><td class="pendiente">$<?= number_format($pagosPorEstado['pendiente'], 2) ?></td></tr>
    </tbody>
</table>

<h3>Totales por Tipo de Pago (Pagados)</h3>
<?= $this->tablaResumen($pagosPorTipoPagado) ?>

<h3>Totales por Tipo de Pago (Pendientes)</h3>
<?= $this->tablaResumen($pagosPorTipoPendiente) ?>

<h3>Totales por Método de Pago</h3>
<?= $this->tablaResumen($pagosPorMetodo) ?>

<?php
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $canvas = $dompdf->getCanvas();
    

   
    $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

    $dompdf->stream('reporte_' . strtolower($nombreMes) . '_' . $anio . '.pdf', ['Attachment' => false]);
}
private function tablaPagos($pagos, $total)
{
    if (empty($pagos)) {
        return "<p style='text-align:center;'>No hay registros en esta categoría.</p>";
    }

    $totalPagados = 0;
    $totalPendientes = 0;

    foreach ($pagos as $pago) {
        if ($pago['status'] === 'pagado') {
            $totalPagados += $pago['amount'];
        } else {
            $totalPendientes += $pago['amount'];
        }
    }

    ob_start();
?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Alumno</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Tipo</th>
            <th>Método</th>
            <th>Estado</th>
            <th>Grupo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagos as $i => $pago): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($pago['alumno']) ?></td>
            <td><?= date('d/m/Y', strtotime($pago['payment_date'])) ?></td>
            <td>$<?= number_format($pago['amount'], 2) ?></td>
            <td><?= htmlspecialchars($pago['type']) ?></td>
            <td><?= htmlspecialchars($pago['metodopago']) ?></td>
            <td class="<?= $pago['status'] === 'pendiente' ? 'pendiente' : '' ?>">
                <?= ucfirst($pago['status']) ?>
            </td>
            <td><?= htmlspecialchars($pago['grupo'] ?? 'Sin Grupo') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Resumen debajo -->
<div style="text-align:center; margin-top:10px;">
    <strong>Total sección:</strong> $<?= number_format($total, 2) ?><br>
    <span style="color:green;"><strong>✔️ Total Pagados:</strong> $<?= number_format($totalPagados, 2) ?></span><br>
    <span style="color:orange;"><strong>⚠️ Total Pendientes:</strong> $<?= number_format($totalPendientes, 2) ?></span>
</div>
<?php
    return ob_get_clean();
}
private function tablaResumen($data)
{
    if (empty($data)) {
        return "<p style='text-align:center;'>No hay datos disponibles.</p>";
    }

    ob_start();
?>
<table>
    <thead>
        <tr><th>Concepto</th><th>Total</th></tr>
    </thead>
    <tbody>
        <?php foreach ($data as $concepto => $monto): ?>
        <tr>
            <td><?= htmlspecialchars($concepto) ?></td>
            <td>$<?= number_format($monto, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
    return ob_get_clean();
}



    

    
}
