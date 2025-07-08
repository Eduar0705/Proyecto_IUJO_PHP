<?php
class SolicitudModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        
    }

    public function crearSolicitud($datos) {
        // Validar campos obligatorios
        if (empty($datos['solicitante']) || empty($datos['titulo']) || empty($datos['tipo'])) {
            throw new Exception("Datos incompletos para crear la solicitud");
        }
        
        // Validar que el tipo sea uno de los permitidos
        $tiposPermitidos = ['oficina', 'comida', 'proyecto'];
        if (!in_array($datos['tipo'], $tiposPermitidos)) {
            throw new Exception("Tipo de solicitud no válido");
        }
        
        // Asegurar que los datos específicos sean un array antes de convertirlos a JSON
        if (!is_array($datos['datos'])) {
            throw new Exception("Los datos específicos deben ser un array");
        }
        $sql = "INSERT INTO solicitudes (
        solicitante,
        id_solicitante,
        titulo,
        tipo,
        datos,
        fecha_creacion,
        fecha_inminente
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?
        )";
    
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['solicitante'],
            $datos['id_solicitante'],
            trim($datos['titulo']),
            $datos['tipo'],
            json_encode($datos['datos']),
            date('Y-m-d H:i:s'),
            $datos['fecha_limite']
        ]);
    }
    public function solicitudesPropias($id) {
            $sql = "SELECT *
                    FROM solicitudes
                    WHERE id_informacion IN (
                        SELECT id_solicitud
                        FROM productos
                        WHERE id_solicitante = $id

                        UNION

                        SELECT id_solicitud
                        FROM proyectos
                        WHERE id_solicitante = $id
                    );";
            $resultado = $this->db->query($sql);
            
            if ($resultado === false) {
                throw new Exception("Error en la consulta: " . $this->db->error);
            }
            
            return $resultado;
        }
    public function cantSolicitudesPropias($id) {
        $solicitudes = [];
            $sql = "SELECT COUNT(*) AS total_rechazadas
                    FROM solicitudes
                    WHERE estado = 'Rechazada'
                    AND id_informacion IN (
                        SELECT id_solicitud
                        FROM productos
                        WHERE id_solicitante = $id

                        UNION

                        SELECT id_solicitud
                        FROM proyectos
                        WHERE id_solicitante = $id
                    );";
                    $rechazadas = $this->db->query($sql);
            
            if ($rechazadas === false) {
                throw new Exception("Error en la consulta: " . $this->db->error);
            }
            $solicitudes['rechazadas'] = $rechazadas->fetch_assoc()['total_rechazadas'];;

            $sql = "SELECT COUNT(*) AS total_aprobadas
                    FROM solicitudes
                    WHERE estado = 'Aprobada'
                    AND id_informacion IN (
                        SELECT id_solicitud
                        FROM productos
                        WHERE id_solicitante = $id

                        UNION

                        SELECT id_solicitud
                        FROM proyectos
                        WHERE id_solicitante = $id
                    );";
                    $aprobadas = $this->db->query($sql);
            
            if ($aprobadas === false) {
                throw new Exception("Error en la consulta: " . $this->db->error);
            }
            $solicitudes['aprobadas'] = $aprobadas->fetch_assoc()['total_aprobadas'];

            $sql = "SELECT COUNT(*) AS total_pendientes
                    FROM solicitudes
                    WHERE estado = 'Pendiente'
                    AND id_informacion IN (
                        SELECT id_solicitud
                        FROM productos
                        WHERE id_solicitante = $id

                        UNION

                        SELECT id_solicitud
                        FROM proyectos
                        WHERE id_solicitante = $id
                    );";
                    $pendientes = $this->db->query($sql);
            
            if ($pendientes === false) {
                throw new Exception("Error en la consulta: " . $this->db->error);
            }
            $solicitudes['pendientes'] = $pendientes->fetch_assoc()['total_pendientes'];

            return $solicitudes;
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

        // Método para obtener todas las solicitudes (para admin)
    public function obtenerTodasSolicitudes($filtroEstado = null) {
        $sql = "SELECT s.*, i.nombre as nombre_usuario 
                FROM solicitudes s
                JOIN informacion i ON s.id_informacion = i.id";
        
        if ($filtroEstado) {
            $sql .= " WHERE s.estado = :estado";
        }
        
        $sql .= " ORDER BY s.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($filtroEstado) {
            $stmt->bindValue(':estado', $filtroEstado);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para actualizar el estado de una solicitud (admin)
    public function actualizarEstadoSolicitud($idSolicitud, $estado, $comentario = null) {
        $sql = "UPDATE solicitudes 
                SET estado = :estado, 
                    comentario_admin = :comentario,
                    fecha_revision = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $idSolicitud,
            ':estado' => $estado,
            ':comentario' => $comentario
        ]);
    }

    // Método para obtener detalles de una solicitud
    public function obtenerSolicitudPorId($idSolicitud) {
        $sql = "SELECT s.*, i.nombre as nombre_usuario 
                FROM solicitudes s
                JOIN informacion i ON s.id_informacion = i.id
                WHERE s.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idSolicitud]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
