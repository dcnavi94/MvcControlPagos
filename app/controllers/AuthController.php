<?php
require_once __DIR__ . '/../../config/database.php';

class AuthController {

    public function login() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function authenticate() {
        session_start();
        global $pdo;

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Seguridad básica: limpiar caracteres raros
        $email = is_string($email) ? preg_replace('/[\x00-\x1F\x7F]/u', '', $email) : '';
        $password = is_string($password) ? preg_replace('/[\x00-\x1F\x7F]/u', '', $password) : '';

        // Buscar usuario
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encontró el usuario, limpiamos su contraseña almacenada
        if ($user) {
            $user['password'] = trim($user['password']);
        }

        // Validar login
        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];           // Muy importante para todo el sistema
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'name' => $user['name']
            ];
            $_SESSION['role'] = $user['role'];            // También guardamos el rol por separado si quieres

            // Redirección basada en rol
            if ($user['role'] === 'admin') {
                header('Location: /dashboard/admin');
            } elseif ($user['role'] === 'alumno') {
                header('Location: /alumno/pagos');
            } else {
                // Si no es admin ni alumno, lo regresamos al login
                header('Location: /auth/login');
            }

        } else {
            // Login fallido
            $_SESSION['error'] = 'Credenciales incorrectas.';
            header('Location: /auth/login');
        }
        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
}
