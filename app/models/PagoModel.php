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
    public function sumarPagosPorEstadoYMES($estado, $mes, $grupos = []) {
        $query = "
            SELECT SUM(amount) as total
            FROM payments p
            INNER JOIN users u ON p.user_id = u.id
            WHERE p.status = ?
              AND MONTH(p.payment_date) = ?
        ";
    
        $params = [$estado, $mes];
    
        if (!empty($grupos)) {
            $placeholders = implode(',', array_fill(0, count($grupos), '?'));
            $query .= " AND u.group_id IN ($placeholders)";
            $params = array_merge($params, $grupos);
        }
    
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
    
    
}
