<?php
class ProyectoModel {
    private $db;
    private $modeloDB;
    
    public function __construct()
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
    }

    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Obtiene todos los proyectos
     */
    public function obtenerTodos($busqueda = '') {
        $sql = "SELECT * FROM proyectos";
        
        if (!empty($busqueda)) {
            $busqueda = $this->db->real_escape_string($busqueda);
            $sql .= " WHERE nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%'";
        }
        
        $sql .= " ORDER BY fecha_registro DESC";
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw new Exception("Error al obtener proyectos: " . $this->db->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Crea un nuevo proyecto
     */
    public function crear($datos) {
        $nombre = $this->db->real_escape_string($datos['nombre']);
        $descripcion = $this->db->real_escape_string($datos['descripcion']);
        $fecha_inicio = $this->db->real_escape_string($datos['fecha_inicio']);
        $fecha_fin = isset($datos['fecha_fin']) ? "'".$this->db->real_escape_string($datos['fecha_fin'])."'" : "NULL";
        $estado = $this->db->real_escape_string($datos['estado']);
        
        $sql = "INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin, estado) 
                VALUES ('$nombre', '$descripcion', '$fecha_inicio', $fecha_fin, '$estado')";
        
        if (!$this->db->query($sql)) {
            throw new Exception("Error al crear proyecto: " . $this->db->error);
        }
        
        return $this->db->insert_id;
    }
    
    /**
     * Actualiza un proyecto existente
     */
    public function actualizar($id, $datos) {
        $id = intval($id);
        $nombre = $this->db->real_escape_string($datos['nombre']);
        $descripcion = $this->db->real_escape_string($datos['descripcion']);
        $fecha_inicio = $this->db->real_escape_string($datos['fecha_inicio']);
        $fecha_fin = isset($datos['fecha_fin']) ? "'".$this->db->real_escape_string($datos['fecha_fin'])."'" : "NULL";
        $estado = $this->db->real_escape_string($datos['estado']);
        
        $sql = "UPDATE proyectos SET 
                nombre = '$nombre',
                descripcion = '$descripcion',
                fecha_inicio = '$fecha_inicio',
                fecha_fin = $fecha_fin,
                estado = '$estado',
                fecha_actualizacion = NOW()
                WHERE id = $id";
        
        if (!$this->db->query($sql)) {
            throw new Exception("Error al actualizar proyecto: " . $this->db->error);
        }
        
        return $this->db->affected_rows;
    }
    
    /**
     * Elimina un proyecto
     */
    public function eliminar($id) {
        $id = intval($id);
        $sql = "DELETE FROM proyectos WHERE id = $id";
        
        if (!$this->db->query($sql)) {
            throw new Exception("Error al eliminar proyecto: " . $this->db->error);
        }
        
        return $this->db->affected_rows;
    }
    
    /**
     * Obtiene un proyecto por ID
     */
    public function obtenerPorId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM proyectos WHERE id = $id";
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw new Exception("Error al obtener proyecto: " . $this->db->error);
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Obtiene estadísticas mensuales para gráficos
     */
    public function obtenerEstadisticas() {
        // Estadísticas por estado (para gráfico de torta)
        $sqlEstados = "SELECT 
                        estado, 
                        COUNT(*) as cantidad,
                        ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM proyectos), 2) as porcentaje
                      FROM proyectos
                      GROUP BY estado";
        
        $resultEstados = $this->db->query($sqlEstados);
        
        if (!$resultEstados) {
            throw new Exception("Error al obtener estadísticas por estado: " . $this->db->error);
        }
        
        // Estadísticas mensuales (para tabla)
        $sqlMensual = "SELECT 
                        YEAR(fecha_registro) as año,
                        MONTH(fecha_registro) as mes,
                        COUNT(*) as total,
                        SUM(estado = 'completado') as completados
                        FROM proyectos
                        GROUP BY YEAR(fecha_registro), MONTH(fecha_registro)
                        ORDER BY año DESC, mes DESC";
        
        $resultMensual = $this->db->query($sqlMensual);
        
        if (!$resultMensual) {
            throw new Exception("Error al obtener estadísticas mensuales: " . $this->db->error);
        }
        
        return [
            'por_estado' => $resultEstados->fetch_all(MYSQLI_ASSOC),
            'mensuales' => $resultMensual->fetch_all(MYSQLI_ASSOC)
        ];
    }
}
?>