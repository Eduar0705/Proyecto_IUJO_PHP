<?php

class Enrutador {
    // Método principal que ejecuta el enrutamiento
    public function ejecutar() {
        // Obtiene el controlador de la URL, o usa 'autenticacion' por defecto
        $controlador = $_GET['controlador'] ?? 'autenticacion';
        // Obtiene el método de la URL, o usa 'login' por defecto
        $metodo = $_GET['metodo'] ?? 'login';

        // Convierte el nombre del controlador a formato adecuado y le añade 'Controlador'
        $controlador = ucfirst($controlador) . 'Controlador';
        // Construye la ruta del archivo del controlador
        $rutaControlador = 'controllers/' . $controlador . '.php';

        // Verifica si el archivo del controlador existe
        if (file_exists($rutaControlador)) {
            // Incluye el archivo del controlador
            require_once $rutaControlador;
            // Verifica si la clase del controlador existe
            if (class_exists($controlador)) {
                // Crea una instancia del controlador
                $obj = new $controlador();
                // Verifica si el método existe en el controlador
                if (method_exists($obj, $metodo)) {
                    // Llama al método solicitado
                    $obj->$metodo();
                    return;
                }
            }
        }

        // Si algo falla, muestra la página de error 404
        include 'views/auth/404error.html';
    }
}
