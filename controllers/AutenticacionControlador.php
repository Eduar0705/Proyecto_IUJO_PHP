<?php
require_once 'model/UsuarioModelo.php';
require_once 'config/base_datos.php';

class AutenticacionControlador {
    private $modelo;
    private $conexion;
    public function __construct() {
        $this->modelo = new UsuarioModelo();
        $this->conexion = BaseDatos::conectar();
    }

    public function login() {
        include 'views/auth/login.php';
        if (isset($_POST['iniciar'])) {
            if (strlen($_POST['usuario']) >= 3 && strlen($_POST['clave']) >= 3) {
            $usuario = trim($_POST['usuario']);
            $clave = trim($_POST['clave']);

            $credenciales = $this->modelo->verificarCredenciales($usuario, $clave);

            if ($credenciales) {
                $_SESSION['nombre'] = $credenciales['usuario'];

                if ($credenciales['id_cargo'] == 1) {
                header("Location: ./views/Admin.php");
                exit();
                } elseif ($credenciales['id_cargo'] == 2) {
                header("Location: ./views/Usuario.php");
                exit();
                }
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos');</script>";
            }
            }
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
            $nombre = $_POST['nombre'] ?? '';
            $usuario = $_POST['username'] ?? '';
            $cedula = $_POST['CI'] ?? '';
            $clave = $_POST['password'] ?? '';
            $email = $_POST['email'] ?? '';
            $cargo = $_POST['cargo'] ?? '';

            $resultado = $this->modelo->registrar($nombre, $usuario, $cedula, $clave, $email, $cargo);
            if ($resultado === true) {
                echo "Usuario registrado correctamente. <a href='index.php'>Iniciar sesión</a>";
                return;
            } else {
                $error = "Error al registrar. Ya existe usuario, email o cédula.";
                include 'views/auth/registro.php';
                return;
            }
        }

        include 'views/auth/registro.php';
    }
}
