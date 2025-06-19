<?php
// Configuración básica
require_once 'config/config.php';

// Autoload básico
spl_autoload_register(function($className) {
    $paths = [
        'controllers/' . $className . '.php',
        'models/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Enrutamiento 
$action = $_GET['action'] ?? 'home';
$controllerName = ucfirst($action) . 'Controller';
$controllerFile = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Verificación de las clases
    if (!class_exists($controllerName)) {
        die("Error: La clase $controllerName no está definida en $controllerFile");
    }
    
    $controller = new $controllerName();
    
    // Verificación del método index 
    if (!method_exists($controller, 'index')) {
        die("Error: El método index() no existe en $controllerName");
    }
    
    $controller->index();
} else {
    error_log("Controlador no encontrado: $controllerFile");
    require_once 'views/error/404.php';
}
