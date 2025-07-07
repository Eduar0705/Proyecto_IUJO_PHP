<?php
class Configuracion {
    private $bd;
    private $modelBD;

    public function __construct() {
        $this->modelBD = new BaseDatos();
        $this->bd = $this->modelBD->conectar();
        
        if (!$this->bd) {
            throw new Exception("Error al conectar a la base de datos");
        }
        
        $this->inicioSesion();
    }

    public function inicioSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function buscarUsuarios() {
        $sql = "SELECT * FROM informacion";
        $resultado = $this->bd->query($sql);
        
        if ($resultado === false) {
            throw new Exception("Error en la consulta: " . $this->bd->error);
        }
        
        return $resultado;
    }
     public function buscarSolicitudProy() {
        $sql = "SELECT * FROM solicitudes";
        $resultado = $this->bd->query($sql);
        
        if ($resultado === false) {
            throw new Exception("Error en la consulta: " . $this->bd->error);
        }
        
        return $resultado;
    }
    public function buscarSolicitud() {
        $sql = "SELECT * FROM solicitud_registro";
        $resultado = $this->bd->query($sql);
        
        if ($resultado === false) {
            throw new Exception("Error en la consulta: " . $this->bd->error);
        }
        
        return $resultado;
    }

    public function eliminarUsuario($id) {
        // Preparar y ejecutar consulta
        $stmt = $this->bd->prepare("DELETE FROM informacion WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta");
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el usuario");
        }

        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();

        return $filasAfectadas;
    }
     public function actualizarSolicitud($id, $nombre, $usuario, $email, $cedula, $id_cargo) {
        $sql = "UPDATE solicitud_registro 
                SET nombre = ?, usuario = ?, email = ?, cedula = ?, id_cargo = ?
                WHERE id = ?";
        
        $stmt = $this->bd->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->bd->error);
        }
        
        $stmt->bind_param("ssssii", $nombre, $usuario, $email, $cedula, $id_cargo, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    }
    public function actualizarUsuario($id, $nombre, $usuario, $email, $cedula, $id_cargo) {
        $sql = "UPDATE informacion 
                SET nombre = ?, usuario = ?, email = ?, cedula = ?, id_cargo = ?
                WHERE id = ?";
        
        $stmt = $this->bd->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->bd->error);
        }
        
        $stmt->bind_param("ssssii", $nombre, $usuario, $email, $cedula, $id_cargo, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    }
    public function __destruct() {
        if ($this->bd) {
            $this->bd->close();
        }
    }
}