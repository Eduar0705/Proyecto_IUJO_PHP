<?php
require_once __DIR__ . '/../model/conexion.php';

class InicioController
{
    private $db; 

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = BaseDatos::conectar();
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
        $hideCarousel = true;
        require_once 'views/auth/login.php';
    }

    public function loginAuthenticate() {
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);
                // Escapar caracteres especiales para prevenir inyección SQL
                $consulta = "SELECT * FROM informacion WHERE usuario = ? AND clave = ?";
                $stmt = mysqli_prepare($this->db, $consulta);
                mysqli_stmt_bind_param($stmt, "ss", $usuario, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($fila = mysqli_fetch_array($result)){
                    $_SESSION['nombre'] = $fila['nombre'];
                    $_SESSION['id'] = $fila['id'];

                    if($fila['id_cargo'] == 1){
                        header("Location: ?action=admin");
                        exit();
                    }
                    else if($fila['id_cargo'] == 2){
                        header("Location: ?action=users");
                        exit();
                    }
                    else{
                        echo "<script>alert('ID no encontrado error en el usuario registrado');</script>";
                    }
                } else {
                    echo "<script>alert('Usuario o contraseña incorrectos');</script>";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    public function register() {
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
                $password2 = ($_POST['password2']);
                //VERIFICACION SI YA SE ENCUENTRAN EN LA BASE DE DATOS
                $stmt = $this->db->prepare("SELECT * FROM informacion WHERE usuario = ? OR email = ? OR cedula = ?");
                $stmt->bind_param("sss", $usuario, $email, $cedula);
                $stmt->execute();
                $resultado_verificar = $stmt->get_result();

                if(mysqli_num_rows($resultado_verificar) > 0) {
                    // SI EL USUARIO, EMAIL O CÉDULA YA EXISTEN EN EL SISTEMA
                    $fila = mysqli_fetch_assoc($resultado_verificar);
                    
                    if($fila['usuario'] == $usuario) {
                        echo "<script>alert('El nombre de usuario ya está registrado');</script>";
                    }
                    
                    if($fila['email'] == $email) {
                        echo "<script>alert('El correo electrónico ya está registrado');</script>";
                    }
                    if($fila['cedula'] == $cedula){
                        echo "<script>alert('La cédula ingresada ya se encuentra en el sistema');</script>";
                    }
                }
                //VERIFICACION SI YA SE ENCUENTRAN SOLICITADOS
                $stmt = $this->db->prepare("SELECT * FROM solicitud_registro WHERE usuario = ? OR email = ? OR cedula = ?");
                $stmt->bind_param("sss", $usuario, $email, $cedula);
                $stmt->execute();
                $resultado_verificar = $stmt->get_result();

                if(mysqli_num_rows($resultado_verificar) > 0) {
                    // SI EL USUARIO, EMAIL O CÉDULA YA FUERON SOLICITADOS
                    $fila = mysqli_fetch_assoc($resultado_verificar);
                    if($fila['usuario'] == $usuario && $fila['email'] == $email && $fila['cedula'] == $cedula) {
                        echo "<script>alert('Ya se una cuenta con estos datos. Inicie sesión o intente más tarde.');</script>";
                    }                   
                } else {
                    $consulta = "INSERT INTO solicitud_registro(nombre, usuario, clave, email, cedula, id_cargo) VALUES ('$names','$usuario','$password','$email', '$cedula','2')";
                    $resultado = mysqli_query($this->db, $consulta); 

                    if ($resultado) {
                        echo "<script>alert('Solicitud de registro enviada');</script>";
                        $this->register();
                    } else {
                        echo "<script>alert('Error al registrar el usuario: " . mysqli_error($this->db) . "');</script>";
                    }
                }
            } else {
                echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
            }
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
            
            // Verificar si el email existe
            $query = "SELECT id FROM informacion WHERE email = ?";
            $stmt = mysqli_prepare($this->db, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            if (!mysqli_stmt_execute($stmt)) {
                error_log("Error al verificar email: " . mysqli_error($this->db));
                header("Location: ?action=inicio&method=forgotPassword&alert=danger&message=Error interno");
                exit();
            }
            
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                // Generar token
                $token = rand(100000, 999999);
                $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
                
                // Debug: Mostrar el token que se generó
                error_log("Token generado: $token");
                error_log("Expira: $expiry");
                
                // Guardar el token
                $insertQuery = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE token = ?, created_at = ?";
                $stmt = mysqli_prepare($this->db, $insertQuery);
                mysqli_stmt_bind_param($stmt, "sssss", $email, $token, $expiry, $token, $expiry);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Debug: Verificar inserción
                    error_log("Token guardado en BD para: $email");
                    
                    // Guardar en sesión
                    $_SESSION['reset_email'] = $email;
                    error_log("Email guardado en sesión: ".$_SESSION['reset_email']);
                    
                    // Enviar email (simulado)
                    $subject = "Código de recuperación de contraseña";
                    $message = "Tu código es: $token\nVálido por 1 hora";
                    $headers = "From: no-reply@tuapp.com";
                    
                    if (@mail($email, $subject, $message, $headers)) {
                        error_log("Email enviado a $email");
                    } else {
                        error_log("Falló el envío de email a $email");
                    }
                    
                    // Redirigir
                    header("Location: ?action=inicio&method=verifyToken");
                    exit();
                } else {
                    error_log("Error al guardar token: " . mysqli_error($this->db));
                    header("Location: ?action=inicio&method=forgotPassword&alert=danger&message=Error al generar código");
                    exit();
                }
            }
            
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
            
            // Verificar el token en la base de datos
            $query = "SELECT * FROM password_resets 
                    WHERE email = ? AND token = ? AND created_at > NOW()";
            $stmt = mysqli_prepare($this->db, $query);
            mysqli_stmt_bind_param($stmt, "ss", $email, $token);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                // Token válido, permitir cambio de contraseña
                $_SESSION['reset_valid'] = true;
                header("Location: ?action=inicio&method=resetPassword");
                exit();
            } else {
                header("Location: ?action=inicio&method=verifyToken&alert=danger&message=Código inválido o expirado");
                exit();
            }
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
            
            // Actualizar la contraseña en la base de datos
            $updateQuery = "UPDATE informacion SET clave = ? WHERE email = ?";
            $stmt = mysqli_prepare($this->db, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $password, $email);
            
            if (mysqli_stmt_execute($stmt)) {
                // Limpiar la sesión y tokens
                $deleteQuery = "DELETE FROM password_resets WHERE email = ?";
                $stmt = mysqli_prepare($this->db, $deleteQuery);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                
                unset($_SESSION['reset_email'], $_SESSION['reset_valid']);
                
                header("Location: ?action=inicio&method=login&alert=success&message=Contraseña actualizada correctamente");
                exit();
            } else {
                header("Location: ?action=inicio&method=resetPassword&alert=danger&message=Error al actualizar la contraseña");
                exit();
            }
        }
        
        header("Location: ?action=inicio&method=resetPassword");
        exit();
    }
}