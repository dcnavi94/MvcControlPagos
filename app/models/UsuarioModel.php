<?php
require_once __DIR__ . '/../../config/database.php'; // Incluye tu conexión PDO

class UsuarioModel {
    private $db;

    public function __construct() {
        global $pdo;
        if (!$pdo) {
            die('Error: No hay conexión disponible.');
        }
        $this->db = $pdo;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("
            SELECT id, name, email, career_id, grade_id, group_id, scholarship
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}