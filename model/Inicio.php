<?php
class Inicio 
{
    private $db; 
    private $modeloDB;
    
    public function __construct() 
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
        $this->iniciarSesion();
    }

    /*
    * Inicia la sesión si no está activa
    */
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /*
    * Autentica el login del usuario
    */
    public function loginAuthenticate($usuario, $password)
    {
        try {
            // Validar parámetros
            if (empty($usuario) || empty($password)) {
                $this->mostrarError("Usuario y contraseña son obligatorios");
                return false;
            }

            // Consulta preparada para prevenir inyección SQL
            $consulta = "SELECT * FROM informacion WHERE usuario = ? AND clave = ?";
            $stmt = mysqli_prepare($this->db, $consulta);
            
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . mysqli_error($this->db));
            }

            mysqli_stmt_bind_param($stmt, "ss", $usuario, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($fila = mysqli_fetch_array($result)) {
                $this->establecerSesionUsuario($fila);
                $this->redirigirSegunRol($fila['id_cargo']);
            } else {
                $this->mostrarErrorLogin("Usuario o contraseña incorrectos");
            }

            mysqli_stmt_close($stmt);
            
        } catch (Exception $e) {
            error_log("Error en loginAuthenticate: " . $e->getMessage());
            $this->mostrarErrorLogin("Error en el sistema. Intente nuevamente");
        }
    }

    /**
    * Establece las variables de sesión del usuario
    */
    private function establecerSesionUsuario($datosUsuario)
    {
        $_SESSION['nombre'] = $datosUsuario['nombre'];
        $_SESSION['id'] = $datosUsuario['id'];
        $_SESSION['id_cargo'] = $datosUsuario['id_cargo'];
    }

    /**
    * Redirige según el rol del usuario
    */
    private function redirigirSegunRol($idCargo)
    {
        switch ($idCargo) {
            case 1:
                $this->redirigirUsuario("Bienvenido, " . $_SESSION['nombre'], "?action=admin");
                break;
            case 2:
                $this->redirigirUsuario("Bienvenido, " . $_SESSION['nombre'], "?action=users");
                break;
            default:
                $this->mostrarErrorLogin("Rol de usuario no válido");
                return;
        }
    }

    /**
    * Registra un nuevo usuario en el sistema
    */
    public function registerStore($names, $usuario, $cedula, $email, $password, $base_datos)
    {
        try {
            // Validar parámetros
            if (!$this->validarDatosRegistro($names, $usuario, $cedula, $email, $password)) {
                return false;
            }

            // Verificar duplicados en tabla principal
            if ($this->existeUsuarioEnTabla('informacion', $usuario, $email, $cedula)) {
                return false;
            }

            // Verificar duplicados en tabla objetivo
            if ($this->existeUsuarioEnTabla($base_datos, $usuario, $email, $cedula)) {
                $this->mostrarError("Ya existe una cuenta con estos datos. Inicie sesión o intente más tarde");
                return false;
            }

            // Insertar nuevo usuario
            return $this->insertarNuevoUsuario($base_datos, $names, $usuario, $password, $email, $cedula);

        } catch (Exception $e) {
            error_log("Error en registerStore: " . $e->getMessage());
            $this->mostrarError("Error en el sistema. Intente nuevamente");
            return false;
        }
    }

    /**
    * Valida los datos de registro
    */
    private function validarDatosRegistro($names, $usuario, $cedula, $email, $password)
    {
        $campos = [
            'nombre' => $names,
            'usuario' => $usuario,
            'cédula' => $cedula,
            'email' => $email,
            'contraseña' => $password
        ];

        foreach ($campos as $nombre => $valor) {
            if (empty(trim($valor))) {
                $this->mostrarError("El campo {$nombre} es obligatorio");
                return false;
            }
        }

        return true;
    }

    /**
    * Verifica si ya existe un usuario en la tabla especificada
    */
    private function existeUsuarioEnTabla($tabla, $usuario, $email, $cedula)
    {
        $consulta = "SELECT usuario, email, cedula FROM {$tabla} WHERE usuario = ? OR email = ? OR cedula = ?";
        $stmt = $this->db->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . mysqli_error($this->db));
        }

        $stmt->bind_param("sss", $usuario, $email, $cedula);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $this->mostrarErroresDuplicados($fila, $usuario, $email, $cedula);
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    /**
    * Muestra errores específicos para datos duplicados
    */
    private function mostrarErroresDuplicados($fila, $usuario, $email, $cedula)
    {
        if ($fila['usuario'] === $usuario) {
            $this->mostrarError("El nombre de usuario ya está registrado");
        }
        if ($fila['email'] === $email) {
            $this->mostrarError("El correo electrónico ya está registrado");
        }
        if ($fila['cedula'] === $cedula) {
            $this->mostrarError("La cédula ingresada ya se encuentra en el sistema");
        }
    }

    /**
    * Inserta un nuevo usuario en la base de datos
    */
    public function insertarNuevoUsuario($tabla, $nombre, $usuario, $password, $email, $cedula)
    {
        $consulta = "INSERT INTO {$tabla} (nombre, usuario, clave, email, cedula, id_cargo) VALUES (?, ?, ?, ?, ?, 2)";
        $stmt = $this->db->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error preparando inserción: " . mysqli_error($this->db));
        }

        $stmt->bind_param("sssss", $nombre, $usuario, $password, $email, $cedula);
        $resultado = $stmt->execute();

        if ($resultado) {
            $mensaje = ($tabla === 'solicitud_registro') 
                ? "Solicitud de registro enviada" 
                : "Nuevo usuario registrado!";
            $this->mostrarExito($mensaje);
            $stmt->close();
            return true;
        } else {
            $error = mysqli_error($this->db);
            $this->mostrarError("Error al registrar usuario: {$error}");
            $stmt->close();
            return false;
        }
    }

    /**
    * Envía enlace de recuperación de contraseña
    */
    public function sendResetLink($email)
    {
        try {
            if (empty($email)) {
                $this->redirigirConError("forgotPassword", "El email es obligatorio");
                return;
            }

            // Verificar si el email existe
            if (!$this->existeEmail($email)) {
                $this->redirigirConError("forgotPassword", "Email no encontrado");
                return;
            }

            // Generar y guardar token
            $token = $this->generarToken();
            if ($this->guardarToken($email, $token)) {
                $this->enviarEmailRecuperacion($email, $token);
                $_SESSION['reset_email'] = $email;
                header("Location: ?action=inicio&method=verifyToken");
                exit();
            }

        } catch (Exception $e) {
            error_log("Error en sendResetLink: " . $e->getMessage());
            $this->redirigirConError("forgotPassword", "Error interno del sistema");
        }
    }

    /**
    * Verifica si existe un email en la base de datos
    */
    private function existeEmail($email)
    {
        $query = "SELECT id FROM informacion WHERE email = ?";
        $stmt = mysqli_prepare($this->db, $query);
        
        if (!$stmt) {
            throw new Exception("Error preparando consulta de email");
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existe = mysqli_num_rows($result) > 0;
        mysqli_stmt_close($stmt);
        
        return $existe;
    }

    /**
    * Genera un token de 6 dígitos
    */
    private function generarToken()
    {
        return rand(100000, 999999);
    }

    /**
    * Guarda el token en la base de datos
    */
    private function guardarToken($email, $token)
    {
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $query = "INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE token = ?, created_at = ?";
        
        $stmt = mysqli_prepare($this->db, $query);
        if (!$stmt) {
            throw new Exception("Error preparando inserción de token");
        }

        mysqli_stmt_bind_param($stmt, "sssss", $email, $token, $expiry, $token, $expiry);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($resultado) {
            error_log("Token guardado: {$token} para {$email}");
        }

        return $resultado;
    }

    /**
    * Envía email de recuperación (simulado)
    */
    private function enviarEmailRecuperacion($email, $token)
    {
        $subject = "Código de recuperación de contraseña";
        $message = "Tu código es: {$token}\nVálido por 1 hora";
        $headers = "From: no-reply@tuapp.com";
        
        if (@mail($email, $subject, $message, $headers)) {
            error_log("Email enviado a {$email}");
        } else {
            error_log("Falló el envío de email a {$email}");
        }
    }

    /**
    * Actualiza la contraseña del usuario
    */
    public function updatePassword($email, $password)
    {
        try {
            if (empty($email) || empty($password)) {
                $this->redirigirConError("resetPassword", "Email y contraseña son obligatorios");
                return;
            }

            // Actualizar contraseña
            $updateQuery = "UPDATE informacion SET clave = ? WHERE email = ?";
            $stmt = mysqli_prepare($this->db, $updateQuery);
            
            if (!$stmt) {
                throw new Exception("Error preparando actualización de contraseña");
            }

            mysqli_stmt_bind_param($stmt, "ss", $password, $email);
            
            if (mysqli_stmt_execute($stmt)) {
                $this->limpiarTokensYSesion($email);
                $this->redirigirConExito("login", "Contraseña actualizada correctamente");
            } else {
                $this->redirigirConError("resetPassword", "Error al actualizar la contraseña");
            }

            mysqli_stmt_close($stmt);
            
        } catch (Exception $e) {
            error_log("Error en updatePassword: " . $e->getMessage());
            $this->redirigirConError("resetPassword", "Error en el sistema");
        }
    }

    /**
    * Limpia tokens y variables de sesión
    */
    private function limpiarTokensYSesion($email)
    {
        // Eliminar token de la base de datos
        $deleteQuery = "DELETE FROM password_resets WHERE email = ?";
        $stmt = mysqli_prepare($this->db, $deleteQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Limpiar variables de sesión
        unset($_SESSION['reset_email'], $_SESSION['reset_valid']);
    }

    /**
    * Verifica el token de recuperación
    */
    public function checkToken($email, $token)
    {
        try {
            if (empty($email) || empty($token)) {
                $this->redirigirConError("verifyToken", "Email y token son obligatorios");
                return;
            }

            // Verificar token en la base de datos
            $query = "SELECT * FROM password_resets 
                        WHERE email = ? AND token = ? AND created_at > NOW()";
            $stmt = mysqli_prepare($this->db, $query);
            
            if (!$stmt) {
                throw new Exception("Error preparando verificación de token");
            }

            mysqli_stmt_bind_param($stmt, "ss", $email, $token);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
                
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['reset_valid'] = true;
                header("Location: ?action=inicio&method=resetPassword");
                exit();
            } else {
                $this->redirigirConError("verifyToken", "Código inválido o expirado");
            }

            mysqli_stmt_close($stmt);
            
        } catch (Exception $e) {
            error_log("Error en checkToken: " . $e->getMessage());
            $this->redirigirConError("verifyToken", "Error en el sistema");
        }
    }

    // MÉTODOS AUXILIARES

    /**
    * Muestra mensaje de error genérico
    */
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

    /**
    * Muestra mensaje de éxito
    */
    private function mostrarExito($mensaje, $tiempo = 3000) 
    {
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <div class='modal fade' id='exitoModal' tabindex='-1' aria-hidden='true' data-bs-backdrop='static'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content border-success'>
                    <div class='modal-header bg-success text-white'>
                        <h5 class='modal-title'>
                            <i class='bi bi-check-circle-fill me-2'></i>
                            ¡Éxito!
                        </h5>
                    </div>
                    <div class='modal-body text-center py-4'>
                        <p class='lead'>{$mensaje}</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Mostrar modal automáticamente
            document.addEventListener('DOMContentLoaded', function() {
                var exitoModal = new bootstrap.Modal(document.getElementById('exitoModal'));
                exitoModal.show();
                
                // Cerrar automáticamente después del tiempo especificado
                setTimeout(function() {
                    exitoModal.hide();
                }, {$tiempo});
            });
        </script>
        ";
    }

    /**
    * Muestra error específico de login y redirige
    */
    private function mostrarErrorLogin($mensaje)
    {
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <style>
            .centered-modal {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            .centered-modal .modal-dialog {
                margin: 0;
                max-width: 500px;
                width: auto;
            }
        </style>

        <div class='modal fade centered-modal' id='errorLoginModal' tabindex='-1' aria-labelledby='errorLoginModalLabel' aria-hidden='true' data-bs-backdrop='static'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header bg-danger text-white'>
                        <h5 class='modal-title' id='errorLoginModalLabel'>
                            <i class='bi bi-exclamation-triangle-fill me-2'></i>
                            Error de Acceso
                        </h5>
                    </div>
                    <div class='modal-body text-center py-4'>
                        <p class='lead'>{$mensaje}</p>
                    </div>
                    <div class='modal-footer justify-content-center'>
                        <button type='button' class='btn btn-danger btn-lg' onclick='redirectToLogin()'>
                            <i class='bi bi-box-arrow-in-left me-2'></i>
                            Volver al Login
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Mostrar el modal automáticamente
            document.addEventListener('DOMContentLoaded', function() {
                var errorModal = new bootstrap.Modal(document.getElementById('errorLoginModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                errorModal.show();
            });
            
            // Función para redireccionar
            function redirectToLogin() {
                window.location.href = '?action=inicio&method=login';
            }
        </script>
        ";
    }

    /**
    * Redirige con mensaje de error
    */
    private function redirigirConError($metodo, $mensaje)
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redireccionando...</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
                body {
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .error-container {
                    max-width: 500px;
                    width: 100%;
                    animation: fadeIn 0.5s ease-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="alert alert-danger alert-dismissible fade show shadow">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-2">Error</h5>
                            <p class="mb-0">{$mensaje}</p>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <div class="spinner-border spinner-border-sm text-danger me-2" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="text-muted">Redireccionando...</small>
                    </div>
                </div>
            </div>

            <!-- Bootstrap JS Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Redirigir después de 3 segundos
                setTimeout(function() {
                    window.location.href = '?action=inicio&method={$metodo}';
                }, 3000);
            </script>
        </body>
        </html>
    HTML;
        exit();
    }

    /**
    * Redirige con mensaje de éxito
    */
    private function redirigirConExito($metodo, $mensaje, $tipo = 'success') 
    {
        // Configuraciones para cada tipo de alerta
        $alertConfig = [
            'success' => [
                'color' => 'success',
                'icon' => 'check-circle-fill',
                'title' => '¡Éxito!',
                'border' => 'border-success',
                'progress' => 'bg-success'
            ],
            'info' => [
                'color' => 'info',
                'icon' => 'info-circle-fill',
                'title' => 'Información',
                'border' => 'border-primary',
                'progress' => 'bg-info'
            ],
            'warning' => [
                'color' => 'warning',
                'icon' => 'exclamation-triangle-fill',
                'title' => 'Advertencia',
                'border' => 'border-warning',
                'progress' => 'bg-warning'
            ],
            'danger' => [
                'color' => 'danger',
                'icon' => 'exclamation-octagon-fill',
                'title' => 'Error',
                'border' => 'border-danger',
                'progress' => 'bg-danger'
            ]
        ];

        // Validar tipo de alerta
        $tipo = in_array($tipo, array_keys($alertConfig)) ? $tipo : 'success';
        $config = $alertConfig[$tipo];

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$config['title']}</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
                body {
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }
                .alert-container {
                    max-width: 500px;
                    width: 100%;
                    animation: fadeInUp 0.5s ease-out;
                }
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .alert-card {
                    border-left: 5px solid var(--bs-{$config['color']});
                }
            </style>
        </head>
        <body>
            <div class="alert-container">
                <div class="card alert-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-{$config['icon']} text-{$config['color']} fs-1 me-3"></i>
                            <div>
                                <h4 class="card-title mb-0 text-{$config['color']}">{$config['title']}</h4>
                            </div>
                        </div>
                        <p class="card-text">{$mensaje}</p>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar {$config['progress']} progress-bar-striped progress-bar-animated" 
                                role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="text-end mt-2">
                            <small class="text-muted">Redireccionando...</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap JS Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Redirigir después de 3 segundos (3000ms)
                setTimeout(function() {
                    window.location.href = '?action=inicio&method={$metodo}';
                }, 3000);
            </script>
        </body>
        </html>
    HTML;
        exit();
    }
    public function redirigirUsuario($mensaje, $url) {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redireccionando</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
                body {
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .redirect-container {
                    max-width: 500px;
                    width: 100%;
                    animation: fadeIn 0.5s ease-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body>
            <div class="redirect-container">
                <div class="card border-success shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>
                        <h4 class="card-title mb-3">{$mensaje}</h4>
                        <div class="progress mb-3" style="height: 5px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                style="width: 100%"></div>
                        </div>
                        <p class="text-muted">Redireccionando...</p>
                    </div>
                </div>
            </div>

            <!-- Bootstrap JS Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                setTimeout(function() {
                    window.location.href = '{$url}';
                }, 3000);
            </script>
        </body>
        </html>
    HTML;
        exit();
    }
}