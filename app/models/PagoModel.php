<?php
require_once __DIR__ . '/../../config/database.php';

class PagoModel {
    private $db;

    public function __construct() {
        global $pdo;
        if (!$pdo) {
            die('Error: No hay conexión disponible.');
        }
        $this->db = $pdo;
    }

    public function obtenerTotalPagosRealizadosMes($mes) {
        $stmt = $this->db->prepare("
            SELECT SUM(amount) AS total
            FROM payments
            WHERE status = 'pagado'
              AND MONTH(payment_date) = ?
              AND YEAR(payment_date) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$mes]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function obtenerTotalPagosPendientesMes($mes) {
        $stmt = $this->db->prepare("
            SELECT SUM(amount) AS total
            FROM payments
            WHERE status = 'pendiente'
              AND MONTH(payment_date) = ?
              AND YEAR(payment_date) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$mes]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
    public function obtenerPagosPorUsuario($userId) {
        $stmt = $this->db->prepare("
            SELECT id, type, amount, status, payment_date
            FROM payments
            WHERE user_id = ?
            ORDER BY payment_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
