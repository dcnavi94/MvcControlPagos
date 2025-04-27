<?php
require_once 'models/PagoModel.php';

class AlumnoPagoController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumno') {
            header("Location: /login");
            exit;
        }

        $alumnoId = $_SESSION['user_id'];
        $model = new PagoModel();
        $pagos = $model->obtenerPagosPorUsuario($alumnoId);

        require 'views/alumno/mis_pagos.php';
    }
}
