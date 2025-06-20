<?php

class InicioController
{
    private $login_bd;
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
    public function register()
    {
        $title = "Registro de Usuario";
        require_once 'views/layout/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/footer.php';
    }
    public function registerStore() 
    {
        //Le falta considerar todos los campos y la base de datos.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
        }
    }
}