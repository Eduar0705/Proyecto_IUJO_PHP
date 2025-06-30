<?php
class SolicitudModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Crear nueva solicitud
    public function crearSolicitud($datos) {
        $sql = "INSERT INTO solicitudes 
                (id_informacion, titulo, tipo, datos, fecha_creacion, estado) 
                VALUES 
                (:id_informacion, :titulo, :tipo, :datos, NOW(), :estado)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_informacion' => $datos['id_usuario'], // Asumo que id_informacion es el ID del usuario
            ':titulo' => $datos['titulo'],
            ':tipo' => $datos['tipo'],
            ':datos' => $datos['datos'], // JSON con los datos específicos
            ':estado' => 'Pendiente'
        ]);
    }

    public function contarSolicitudesPorEstado($idUsuario, $estado) {
        $sql = "SELECT COUNT(*) as total FROM solicitudes 
                WHERE id_informacion = ? AND estado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario, $estado]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public function obtenerUltimasSolicitudes($idUsuario, $limite = 5) {
        $sql = "SELECT id, titulo, tipo, fecha_creacion, estado 
                FROM solicitudes 
                WHERE id_informacion = ? 
                ORDER BY fecha_creacion DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudesUsuario($idUsuario) {
        $sql = "SELECT id, id_informacion, titulo, tipo, datos, fecha_creacion, estado, comentario_admin, fecha_revision 
                FROM solicitudes 
                WHERE id_informacion = ? 
                ORDER BY fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudPorId($idSolicitud, $idUsuario) {
        $sql = "SELECT id, id_informacion, titulo, tipo, datos, 
                    fecha_creacion, estado, comentario_admin, fecha_revision 
                FROM solicitudes 
                WHERE id = ? AND id_informacion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idSolicitud, $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /* Obtener solicitudes por usuario
    public function obtenerPorUsuario($idUsuario) {
        $query = "SELECT * FROM solicitudes WHERE id_usuario = ? ORDER BY fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function crear($idUsuario, $tipo, $descripcion) {
        $query = "SELECT * FROM solicitudes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPorUsuario($idUsuario, $filtro = 'todas') {
        $query = "SELECT * FROM solicitudes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function contarPorEstado($idUsuario, $estado) {
        $query = "SELECT * FROM solicitudes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerUltimas($idUsuario, $limite) {
        $query = "SELECT * FROM solicitudes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPorId($id, $idUsuario) {
        $query = "SELECT * FROM solicitudes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }*/

}
