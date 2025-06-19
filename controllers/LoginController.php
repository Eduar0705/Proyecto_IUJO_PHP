<?php
class LoginController {
    private $bd;
    public function conect() {
        $this->bd =  BaseDatos::conectar();
    }
    public function index() {
        $title = "Iniciar Sesión";
        $hideCarousel = true;
        
        require_once 'views/layout/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/layout/footer.php';

    }
    
    public function authenticate() {
        if(isset($_POST['init'])){
            if(strlen($_POST['user']) >= 3 && strlen($_POST['password']) >= 3) {
                $usuario = trim($_POST['user']);
                $password = trim($_POST['password']);

                $consulta = "SELECT * FROM informacion WHERE usuario = '$usuario' AND clave = '$password'";
                $qery = mysqli_query($this->bd, $consulta);

                if($fila = mysqli_fetch_array($qery)){
                    if($fila['id_cargoo'] == 1){
                        header("Location: ?action=views/admin.php");
                        exit();
                    }
                    else if($fila['id_cargo'] == 2){
                        header("Location: ?action=views/users.php");
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
}