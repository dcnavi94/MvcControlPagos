<?php
require_once '../config/database.php';
ob_start();

// 1. Cargar tabla de rutas limpias
$routes = require_once '../app/routes.php';

// 2. Detectar ruta limpia o ?url=...
$uri = $_GET['url'] ?? ''; // soporte actual
$uri = $uri === '' ? '/' : '/' . trim($uri, '/'); // convertir en ruta tipo "/login"

// Normaliza con FILTER_SANITIZE_URL
$uri = explode('?', filter_var($uri, FILTER_SANITIZE_URL))[0];

// 3. Buscar en el array de rutas
if (isset($routes[$uri])) {
    [$controllerName, $methodName] = $routes[$uri];
} else {
    // fallback: tratar como controlador/metodo en URL (?url=controlador/metodo)
    $parts = explode('/', ltrim($uri, '/'));
    $controllerName = ucfirst($parts[0] ?? 'home') . 'Controller';
    $methodName = $parts[1] ?? 'index';
}

// 4. Cargar controlador
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    echo "Controlador '$controllerName' no encontrado.";
    ob_end_flush();
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    echo "Clase '$controllerName' no encontrada en el archivo.";
    ob_end_flush();
    exit;
}

$controller = new $controllerName();

if (!method_exists($controller, $methodName)) {
    echo "Método '$methodName' no encontrado en '$controllerName'.";
    ob_end_flush();
    exit;
}

// 5. Ejecutar
$controller->{$methodName}();
ob_end_flush();
