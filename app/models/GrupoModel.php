<?php
require_once __DIR__ . '/../../config/database.php';

class GrupoModel
{
    protected $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Obtener todos los grupos.
     *
     * @return array
     */
    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT id, name FROM grupo ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener un grupo específico por ID.
     *
     * @param int $id
     * @return array|null
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT id, name FROM grupo WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

