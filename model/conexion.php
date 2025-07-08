<?php
class BaseDatos {

    public function conectar() {
        $conexion = mysqli_connect("mysql-invilara.alwaysdata.net", "invilara", "3146invilara2025", "invilara_bd");
        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        return $conexion;
    }
}
?>