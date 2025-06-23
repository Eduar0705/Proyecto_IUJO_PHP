<?php
require_once __DIR__ . '/../model/Conexion.php';
require_once __DIR__ . '/../model/Inicio.php';
class InicioController
{
    public $controlador;
    public function __construct()
    {
        $this->controlador = new Inicio();
    }
    public function about() {
        $title = "Sobre Nosotros";
        require_once 'views/layout/header.php';
        require_once 'views/about/index.php';
        require_once 'views/layout/footer.php';
    }
    public function contact() {
        $title = "Contáctanos";
        require_once 'views/layout/header.php';
        require_once 'views/contact/index.php';
        require_once 'views/layout/footer.php';
    }
    public function home() {
        $title = "Inicio";
        require_once 'views/layout/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layout/footer.php';
    }
    public function login() {
        $title = "Iniciar Sesión";
        $hideCarousel = true; //Esto no esta haciendo nada??
        require_once 'views/auth/login.php';
    }
    public function loginAuthenticate() {
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);
                $this->controlador->loginAuthenticate($usuario, $password);
            }
        }
    }
    public function register() {
        $hideCarousel = true; //Esto no esta haciendo nada??
        $title = "Registro de Usuario";
        require_once 'views/auth/register.php';
    }
    public function registerStore() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $names = trim($_POST['nombre']);
            $usuario = trim($_POST['username']);
            $cedula = trim($_POST['CI']);
            $email = trim($_POST['email']);
            if(($_POST['password']) == ($_POST['password2'])) {
                $password = ($_POST['password']);
                $this->controlador->registerStore($names, $usuario, $cedula, $email, $password);
            }
            else {
                echo "<script>alert('Por favor, coloque la misma contraseña en ambos campos.');</script>";
            }
            $this->register();
        }
    }
    public function forgotPassword() {
        $title = "Recuperar Contraseña";
        require_once 'views/auth/forgot-password.php';
    }

    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ?action=inicio&method=forgotPassword&alert=danger&message=Email inválido");
                exit();
            }
            $this->controlador->sendResetLink($email);
            // Mensaje genérico
            header("Location: ?action=inicio&method=forgotPassword&alert=success&message=Si el email existe, recibirás un código");
            exit();
        }
    }

    public function verifyToken() {
        $title = "Verificar Código";
        
        // Verificar que el email está en sesión
        if (!isset($_SESSION['reset_email'])) {
            header("Location: ?action=inicio&method=forgotPassword");
            exit();
        }
        
        require_once 'views/auth/verify-token.php';
    }

    public function checkToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['reset_email'] ?? '';
            $token = trim($_POST['token']);
            
            if (empty($email) || empty($token)) {
                header("Location: ?action=inicio&method=resetPassword");
                exit();
            }
            $this->controlador->checkToken($email, $token);
        }
    }

    public function resetPassword() {
        if (!isset($_SESSION['reset_valid'])) {
            header("Location: ?action=inicio&method=resetPassword");
            exit();
        }
        
        $title = "Cambiar Contraseña";
        require_once 'views/auth/reset-password.php';
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['reset_valid'])) {
            $email = $_SESSION['reset_email'] ?? '';
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if (empty($email) || empty($password) || $password !== $confirm_password) {
                header("Location: ?action=inicio&method=resetPassword&alert=danger&message=Las contraseñas no coinciden");
                exit();
            }
            $this->controlador->updatePassword($email, $password);
        }
        
        header("Location: ?action=inicio&method=resetPassword");
        exit();
    }
}