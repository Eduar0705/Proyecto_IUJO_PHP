<?php
session_start();
class BaseDatos {
    public static function chequearSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function conectar() {
        $conexion = mysqli_connect("localhost", "root", "", "proyecto");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        return $conexion;
    }
    public function verificarUsuario()
    {
        $this->chequearSesion();
        $nombre = "Invitado";
        if (!empty($_SESSION['nombre']) && is_string($_SESSION['nombre'])) {
            $nombre = htmlspecialchars(trim($_SESSION['nombre']), ENT_QUOTES, 'UTF-8');
    }
        $id_carguito = $_SESSION['id_cargo'];
        if ($id_carguito!==1)
        {
            var_dump($_SESSION['id_cargo']);
            exit();
        }
    }
}
?>