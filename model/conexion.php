<?php
class BaseDatos {
    public static function conectar() {
        $conexion = mysqli_connect("localhost", "root", "", "proyecto");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        return $conexion;
    }
}
?>