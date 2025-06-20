<?php
include_once("./model/conexion.php");
class InicioController
{
    private $bd;
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
        
        require_once 'views/layout/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/layout/footer.php';
    }
    public function loginConect() {
        $this->bd =  BaseDatos::conectar();
    }
    public function loginAuthenticate() {
        $this->bd =  BaseDatos::conectar();
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);

                $consulta = "SELECT * FROM informacion WHERE usuario = '$usuario' AND clave = '$password'";
                $qery = mysqli_query($this->bd, $consulta);

                if($fila = mysqli_fetch_array($qery)){
                    if($fila['id_cargo'] == 1){
                        header("Location: views/admin.php");
                        exit();
                    }
                    else if($fila['id_cargo'] == 2){
                        header("Location: views/users.php");
                        exit();
                    }
                    else{
                        echo "<script>alert('ID no encontrado error en el usuario registrado');</script>";
                    }
                }else{
                    echo "<script>alert('Usuario o contraseña incorrectos');</script>";
                }
            }
        }
    }
    public function register()
    {
        $title = "Registro de Usuario";
        require_once 'views/layout/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/footer.php';
    }
    public function registerStore() 
    {
        $this->bd =  BaseDatos::conectar();
        //Le falta considerar todos los campos y la base de datos.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $names = trim($_POST['nombre']);
            $usuario = trim($_POST['username']);
            $cedula = trim($_POST['CI']);
            $email = trim($_POST['email']);
            if(($_POST['password']) == ($_POST['password2']))
            {
                $password = ($_POST['password']);
                $password2 = ($_POST['password2']);
                //VERIFICACION SI YA SE ENCUENTRAN EN LA BASE DE DATOS
                $stmt = $this->bd->prepare("SELECT * FROM informacion WHERE usuario = ? OR email = ? OR cedula = ?");
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
                $stmt = $this->bd->prepare("SELECT * FROM solicitud_registro WHERE usuario = ? OR email = ? OR cedula = ?");
                $stmt->bind_param("sss", $usuario, $email, $cedula);
                $stmt->execute();
                $resultado_verificar = $stmt->get_result();

                if(mysqli_num_rows($resultado_verificar) > 0) {
                // SI EL USUARIO, EMAIL O CÉDULA YA FUERON SOLICITADOS
                    $fila = mysqli_fetch_assoc($resultado_verificar);
                    if($fila['usuario'] == $usuario && $fila['email'] == $email && $fila['cedula'] == $cedula)
                    {
                        echo "<script>alert('Ya se una cuenta con estos datos. Inicie sesión o intente más tarde.');</script>";
                    }                   
                }
                else{
                    $consulta = "INSERT INTO solicitud_registro(nombre, usuario, clave, email, cedula, id_cargo) VALUES ('$names','$usuario','$password','$email', '$cedula','2')";
                    $resultado = mysqli_query($this->bd, $consulta); 

                    if ($resultado) {
                        echo "<script>alert('Solicitud de registro enviada');</script>";
                        $this->register();
                    } else {
                        echo "<script>alert('Error al registrar el usuario: " . mysqli_error($this->bd) . "');</script>";
                    }
                }
            } else {
                echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
            }
        }
    }
}