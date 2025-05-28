<?php
class BaseDatos {
    public static function conectar() {
        $conexion = new mysqli('localhost', 'root', '', 'proyecto');
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        return $conexion;
    }
}
?>
