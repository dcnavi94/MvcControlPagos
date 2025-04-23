<?php
require_once __DIR__ . '/../../config/database.php';

class Grupo
{
    public static function all()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT id, name FROM grupo ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
