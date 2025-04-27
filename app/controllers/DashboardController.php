<?php
// Ajustar la ruta a la base de datos
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/PagoModel.php'; // 👈 OBLIGATORIO: antes de usar PagoModel




class DashboardController
{
    public function index() {
        $pagoModel = new PagoModel();

        // Capturamos el mes seleccionado o usamos el actual
        $mes = isset($_GET['mes']) ? (int) $_GET['mes'] : date('n'); // 'n' => número de mes sin ceros

        $montoPagadoMes = $pagoModel->obtenerTotalPagosRealizadosMes($mes);
        $montoPendienteMes = $pagoModel->obtenerTotalPagosPendientesMes($mes);

        require __DIR__ . '/../views/dashboard/admin.php';
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
        require_once __DIR__ . '/../views/dashboard/reportes.php';
    }
    
}
