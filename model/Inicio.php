<?php
class Inicio{
    private $db; 
    private $modeloDB;
    public function __construct() {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
    }
    public function loginAuthenticate($usuario, $password)
    {
        // Escapar caracteres especiales para prevenir inyección SQL
            $consulta = "SELECT * FROM informacion WHERE usuario = ? AND clave = ?";
            $stmt = mysqli_prepare($this->db, $consulta);
            mysqli_stmt_bind_param($stmt, "ss", $usuario, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($fila = mysqli_fetch_array($result)){
                mysqli_stmt_close($stmt);
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['id'] = $fila['id'];
                $_SESSION['id_cargo'] = $fila['id_cargo'];

                if($_SESSION['id_cargo'] == 1){
                    header("Location: ?action=admin");
                }
                else if($fila['id_cargo'] == 2){
                    header("Location: ?action=users");
                }
                else{
                    "<script>
                    alert('ID no encontrado error en el usuario registrado');
                    window.location.href = '?action=inicio&method=login';
                    </script>";
                }
                exit();
            }  
            else {
                mysqli_stmt_close($stmt);
                echo "<script>
                    alert('Usuario o contraseña incorrectos. Ingreselos con cuidado nuevamente.');
                    window.location.href = '?action=inicio&method=login';
                    </script>";
            }
    }
    
    public function registerStore($names, $usuario, $cedula, $email, $password, $base_datos)
{
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
                $stmt = $this->db->prepare("SELECT * FROM " . $base_datos . " WHERE usuario = ? OR email = ? OR cedula = ?");
                $stmt->bind_param("sss", $usuario, $email, $cedula);
                $stmt->execute();
                $resultado_verificar = $stmt->get_result();

                if(mysqli_num_rows($resultado_verificar) > 0) {
                    // SI EL USUARIO, EMAIL O CÉDULA YA FUERON SOLICITADOS
                    $fila = mysqli_fetch_assoc($resultado_verificar);
                    if($fila['usuario'] == $usuario || $fila['email'] == $email || $fila['cedula'] == $cedula) {
                        echo "<script>alert('Ya existe una cuenta con estos datos. Inicie sesión o intente más tarde.');</script>";
                    }                   
                } else {
                    $consulta = "INSERT INTO " . $base_datos . "(nombre, usuario, clave, email, cedula, id_cargo) VALUES ('$names','$usuario','$password','$email', '$cedula','2')";
                    $resultado = mysqli_query($this->db, $consulta); 

                    if ($resultado) {
                        if($base_datos == 'solicitud_registro')
                        {
                            echo "<script>alert('Solicitud de registro enviada');</script>";
                        }
                        else if($base_datos == 'informacion')
                        {
                            echo "<script>alert('Nuevo usuario registrado!');</script>";
                        }
                        
                    } else {
                        echo "<script>alert('Intente de nuevo. Hubo un error al registrar el usuario: " . mysqli_error($this->db) . "');</script>";
                    }
                }
} 
    public function sendResetLink($email)
    {
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
    }
    public function updatePassword($email, $password)
    {
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
    public function checkToken($email, $token)
    {
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
?>