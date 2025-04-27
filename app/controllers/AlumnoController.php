<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PagoModel.php';

class AlumnoController {

    private static function auth() {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumno') {
            header('Location: /login');
            exit;
        }
    }

    public function perfil() {
        self::auth();

        $model = new UsuarioModel();
        $alumno = $model->obtenerPorId($_SESSION['user_id']);
        
        require __DIR__ . '/../views/alumno/perfil.php';
    }

    public function pagos() {
        self::auth();

        $model = new PagoModel();
        $pagos = $model->obtenerPagosPorUsuario($_SESSION['user_id']);
        
        require __DIR__ . '/../views/alumno/mis_pagos.php';
    }

    public function guardarPerfil() {
        session_start();

        $userId = $_SESSION['user_id'];
        $nombre = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $model = new UsuarioModel();
        $actualizado = $model->actualizarPerfil($userId, $nombre, $email, $password);

        if ($actualizado) {
            // Actualizar también en la sesión para reflejar cambios en vivo
            $_SESSION['user']['name'] = $nombre;
            $_SESSION['user']['email'] = $email;

            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el perfil.']);
        }
    }
}
