<?php
require_once './model/Conexion.php';
require_once __DIR__ . '/../model/Inicio.php';
class InicioController
{
    public $modelo;
    public function __construct()
    {
        $this->modelo = new Inicio();
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
    public function projects(){
        $title = "Proyectos";
        require_once 'views/layout/header.php';
        require_once 'views/Noticias/index.php';
        require_once 'views/layout/footer.php';
    }
    public function login() {
        $title = "Iniciar Sesión";
        require_once 'views/auth/login.php';
    }
    public function loginAuthenticate() {
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);
                $this->modelo->loginAuthenticate($usuario, $password);
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
            $base_datos = 'solicitud_registro';
            $names = trim($_POST['nombre']);
            $usuario = trim($_POST['username']);
            $cedula = trim($_POST['CI']);
            $email = trim($_POST['email']);
            if(($_POST['password']) == ($_POST['password2'])) {
                $password = ($_POST['password']);
                $this->modelo->registerStore($names, $usuario, $cedula, $email, $password, $base_datos);
            }
            else {
                $this->mostrarError("Por favor, coloque la misma contraseña en ambos campos.");
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
            $this->modelo->sendResetLink($email);
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
            $this->modelo->checkToken($email, $token);
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
            $this->modelo->updatePassword($email, $password);
        }
        
        header("Location: ?action=inicio&method=resetPassword");
        exit();
    }
    private function mostrarError($mensaje, $tipo = 'error') 
    {
        // Tipos soportados y sus correspondientes estilos Bootstrap
        $tiposPermitidos = [
            'error' => ['color' => 'danger', 'icon' => 'exclamation-triangle-fill', 'title' => 'Error'],
            'success' => ['color' => 'success', 'icon' => 'check-circle-fill', 'title' => 'Éxito'],
            'warning' => ['color' => 'warning', 'icon' => 'exclamation-triangle-fill', 'title' => 'Advertencia'],
            'info' => ['color' => 'info', 'icon' => 'info-circle-fill', 'title' => 'Información'],
            'question' => ['color' => 'primary', 'icon' => 'question-circle-fill', 'title' => 'Confirmación']
        ];
        
        // Validar y establecer tipo por defecto
        $tipo = array_key_exists(strtolower($tipo), $tiposPermitidos) ? strtolower($tipo) : 'error';
        $config = $tiposPermitidos[$tipo];
        
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <div class='position-fixed top-0 end-0 p-3' style='z-index: 1100'>
            <div id='alertToast' class='toast show' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='5000'>
                <div class='toast-header bg-{$config['color']} text-white'>
                    <i class='bi bi-{$config['icon']} me-2'></i>
                    <strong class='me-auto'>{$config['title']}</strong>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='toast' aria-label='Close'></button>
                </div>
                <div class='toast-body'>
                    {$mensaje}
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('alertToast');
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();
                
                // Pausar cuando el mouse entra
                toastEl.addEventListener('mouseenter', function() {
                    toast._config.autohide = false;
                    toast._clearTimeout();
                });
                
                // Reanudar cuando el mouse sale
                toastEl.addEventListener('mouseleave', function() {
                    toast._config.autohide = true;
                    toast._config.delay = 3000; // Tiempo restante reducido
                    toast._setTimeout();
                });
            });
        </script>
        ";
    }
}