<?php
require_once __DIR__ . '/../../config/database.php';

class AuthController {

    public function login() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function authenticate() {
        session_start();
        global $pdo;

       

        $password = trim($_POST['password'] ?? '');
        $password = is_string($password) ? preg_replace('/[\x00-\x1F\x7F]/u', '', $password) : '';
        


        $email = trim($_POST['email'] ?? '');
        $email = is_string($email) ? preg_replace('/[\x00-\x1F\x7F]/u', '', $email) : '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

          // Si se encontró el usuario, eliminamos espacios en blanco del hash
          if ($user) {
            $user['password'] = trim($user['password']);
        }

    

       /* 
       $plaintext = "loco";
        $hash = password_hash($plaintext, PASSWORD_BCRYPT);
        var_dump(password_verify($plaintext, $hash));
        var_dump($user);
        var_dump($user['password']);
        var_dump(password_verify('loco', $user['password']));
        exit;
        */
       


        if ($user && password_verify(($password),($user['password']))) {
        //if ($user && trim($user['password']) === $password) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'name' => $user['name']
            ];

            if ($user['role'] === 'admin') {
                header('Location: /dashboard/admin');
            } else {
                header('Location: /dashboard/alumno');
            }
        } else {
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
