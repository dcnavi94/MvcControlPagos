<?php
require_once __DIR__ . '/../../config/database.php';

class Grade
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT id, name FROM grades ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
