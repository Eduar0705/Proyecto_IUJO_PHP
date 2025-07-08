<?php
class ContactoModel {
    private $db;
    private $modeloDB;
    
    public function __construct()
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
        $this->iniciarSesion();
    }

    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function crearContacto($datos) {
        $nombre = $this->db->real_escape_string($datos['nombre']);
        $email = $this->db->real_escape_string($datos['email']);
        $telefono = $this->db->real_escape_string($datos['telefono'] ?? '');
        $cargo = $this->db->real_escape_string($datos['cargo'] ?? '');
        
        $sql = "INSERT INTO contactos (nombre, email, telefono, cargo) 
                VALUES ('$nombre', '$email', '$telefono', '$cargo')";
        
        return $this->db->query($sql);
    }

    /**
     * Actualiza un contacto existente
     */
    public function actualizarContacto($id, $datos) {
        $id = intval($id);
        $nombre = $this->db->real_escape_string($datos['nombre']);
        $email = $this->db->real_escape_string($datos['email']);
        $telefono = $this->db->real_escape_string($datos['telefono'] ?? '');
        $cargo = $this->db->real_escape_string($datos['cargo'] ?? '');
        
        $sql = "UPDATE contactos SET 
                nombre = '$nombre', 
                email = '$email', 
                telefono = '$telefono', 
                cargo = '$cargo'
                WHERE id = $id";
        
        return $this->db->query($sql);
    }

    /**
     * Elimina un contacto
     */
    public function eliminarContacto($id) {
        $id = intval($id);
        $sql = "DELETE FROM contactos WHERE id = $id";
        return $this->db->query($sql);
    }

    public function obtenerTodos($busqueda = '') {
    $sql = "SELECT * FROM contactos";
    
    if (!empty($busqueda)) {
        $busqueda = $this->db->real_escape_string($busqueda);
        $sql .= " WHERE nombre LIKE '%$busqueda%' 
                OR email LIKE '%$busqueda%' 
                OR cargo LIKE '%$busqueda%'";
    }
    
    $result = $this->db->query($sql);
    
    // Verifica si hay error en la consulta
    if (!$result) {
        die("Error en la consulta: " . $this->db->error);
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

    public function obtenerContactoPorId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM contactos WHERE id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>