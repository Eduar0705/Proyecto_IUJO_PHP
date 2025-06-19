<?php
class RegisterController {
    public function index() {
        $title = "Registro de Usuario";
        require_once 'views/layout/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/footer.php';
    }
    
    public function store() {
        // Validar los datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
        }
    }
}