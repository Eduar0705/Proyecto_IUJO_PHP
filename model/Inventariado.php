<?php
class Inventariado
{
    public static function refrescarDatos($conex)
    {
    if (isset($_SESSION['nombre'])) {
    $nombre = htmlspecialchars($_SESSION['nombre']);
        } else {
            $nombre = "Invitado";
        }

        if (!$conex) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        
        $search = $_GET['search'] ?? '';
        $categoria_id = $_GET['categoria_id'] ?? '';

        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p
                LEFT JOIN categorias c ON p.cantegoria_id = c.id
                WHERE 1=1";


        $params = [];
        $types = '';

        if (!empty($search)) {
            $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= 'ss';
        }

        if (!empty($categoria_id)) {
            $sql .= " AND p.cantegoria_id = ?";
            $params[] = $categoria_id;
            $types .= 'i';
        }

        $sql .= " ORDER BY p.fecha_registro DESC";


        $stmt = $conex->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta: " . $conex->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $categorias = $conex->query("SELECT * FROM categorias")->fetch_all(MYSQLI_ASSOC);

        return ['productos' => $productos, 'categorias' => $categorias];
    }
    /*public function crear()
    {
        session_start();
        $categorias = $conex->query("SELECT * FROM categorias")->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $conex->real_escape_string($_POST['nombre']);
            $descripcion = $conex->real_escape_string($_POST['descripcion']);
            $cantegoria_id = intval($_POST['cantegoria_id']);
            $unidad_medida = $conex->real_escape_string($_POST['unidad_medida']);
            $stock = intval($_POST['stock']);

            $sql = "INSERT INTO productos (nombre, descripcion, cantegoria_id, unidad_medida, stock) 
                    VALUES ('$nombre', '$descripcion', $cantegoria_id, '$unidad_medida', $stock)";

            if ($conex->query($sql)) {
                header("Location: Index_inventario.php?success=1");
                exit;
            } else {
                $error = "Error al crear producto: " . $conex->error;
            }
        }
    }        */    
}
?>
?>