<?php
require_once __DIR__ . '/../../config/database.php';

class Career
{
    public static function all()
    {
        global $pdo; // <- esta línea es crucial
        $stmt = $pdo->query("SELECT id, name FROM careers ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
