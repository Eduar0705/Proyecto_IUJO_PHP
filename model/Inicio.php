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
                header("Location: ?action=admin");
                break;
            case 2:
                header("Location: ?action=users");
                break;
            default:
                $this->mostrarErrorLogin("Rol de usuario no válido");
                return;
        }
        exit();
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
    private function insertarNuevoUsuario($tabla, $nombre, $usuario, $password, $email, $cedula)
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
    private function mostrarError($mensaje)
    {
        echo "<script>alert('Error: {$mensaje}');</script>";
    }

    /**
    * Muestra mensaje de éxito
    */
    private function mostrarExito($mensaje)
    {
        echo "<script>alert('{$mensaje}');</script>";
    }

    /**
    * Muestra error específico de login y redirige
    */
    private function mostrarErrorLogin($mensaje)
    {
        echo "<script>
            alert('{$mensaje}');
            window.location.href = '?action=inicio&method=login';
        </script>";
    }

    /**
    * Redirige con mensaje de error
    */
    private function redirigirConError($metodo, $mensaje)
    {
        header("Location: ?action=inicio&method={$metodo}&alert=danger&message=" . urlencode($mensaje));
        exit();
    }

    /**
    * Redirige con mensaje de éxito
    */
    private function redirigirConExito($metodo, $mensaje)
    {
        header("Location: ?action=inicio&method={$metodo}&alert=success&message=" . urlencode($mensaje));
        exit();
    }
}