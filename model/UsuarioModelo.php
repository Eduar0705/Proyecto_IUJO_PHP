<?php
include 'config/base_datos.php';

class UsuarioModelo {
    private $conexion;
    public function __construct() {
        $this->conexion = BaseDatos::conectar();
    }

    public function verificarCredenciales($usuario, $clave) {
        $usuario = mysqli_real_escape_string($this->conexion, $usuario);
        $sql = "SELECT usuario, clave, id_cargo FROM informacion WHERE usuario = '$usuario' LIMIT 1";
        $res = mysqli_query($this->conexion, $sql);
        if ($row = mysqli_fetch_assoc($res)) {
            if (password_verify($clave, $row['clave'])) {
                return ['usuario' => $row['usuario'], 'id_cargo' => $row['id_cargo']];
            }
        }
        return false;
    }

    public function registrar($nombre, $usuario, $cedula, $clave, $email, $cargo) {
        $nombre = mysqli_real_escape_string($this->conexion, $nombre);
        $usuario = mysqli_real_escape_string($this->conexion, $usuario);
        $cedula = mysqli_real_escape_string($this->conexion, $cedula);
        $email = mysqli_real_escape_string($this->conexion, $email);
        $cargo = (int)$cargo;

        $query = "SELECT usuario, email, cedula FROM informacion 
                    WHERE usuario = '$usuario' OR email = '$email' OR cedula = '$cedula' LIMIT 1";
        $result = mysqli_query($this->conexion, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return false;
        }

        $claveHash = password_hash($clave, PASSWORD_DEFAULT);
        $query = "INSERT INTO informacion (nombre, usuario, cedula, clave, email, id_cargo, fecha) 
                    VALUES ('$nombre', '$usuario', '$cedula', '$claveHash', '$email', $cargo, NOW())";
        return mysqli_query($this->conexion, $query);
    }

    public function __destruct() {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }
}
