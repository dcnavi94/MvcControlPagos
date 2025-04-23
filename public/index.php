<?php
require_once '../config/database.php';

ob_start(); // <-- Importante para evitar envío prematuro de headers

$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

$controllerName = ucfirst($url[0]) . 'Controller';
$methodName = $url[1] ?? 'index';

$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $methodName)) {
            $controller->{$methodName}();
        } else {
            echo "Método '$methodName' no encontrado.";
        }
    } else {
        echo "Clase '$controllerName' no encontrada en el archivo.";
    }
} else {
    echo "Controlador '$controllerName' no encontrado.";
}

ob_end_flush(); // <-- Al final, libera la salida almacenada
