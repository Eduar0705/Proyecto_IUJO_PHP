<?php
class Inventariado
{
    private $db;
    private $modeloDB;
    public function __construct()
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
    }
    public function getModeloDB()
    {
        return $this->modeloDB;
    }
    public function getDB()
    {
        return $this->db;
    }
    public function refrescarDatos()
    {
    if (isset($_SESSION['nombre'])) {
    $nombre = htmlspecialchars($_SESSION['nombre']);
        } else {
            $nombre = "Invitado";
        }

        if (!$this->db) {
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


        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Error al preparar consulta: " . $this->db->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $categorias = $this->db->query("SELECT * FROM categorias")->fetch_all(MYSQLI_ASSOC);

        return ['productos' => $productos, 'categorias' => $categorias];
    }
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->db->real_escape_string($_POST['nombre']);
            $descripcion = $this->db->real_escape_string($_POST['descripcion']);
            $cantegoria_id = intval($_POST['cantegoria_id']);
            $unidad_medida = $this->db->real_escape_string($_POST['unidad_medida']);
            $stock = intval($_POST['stock']);
            $sql = "INSERT INTO productos (nombre, descripcion, cantegoria_id, unidad_medida, stock) 
                    VALUES ('$nombre', '$descripcion', $cantegoria_id, '$unidad_medida', $stock)";
            if ($this->db->query($sql)) {
                exit();
            } else {
                $error = "Error al crear producto: " . $this->db->error;
            }
        }
    }
    public function eliminar($id)
    {

        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);

        if($stmt->execute()){
            echo '<script> alert("Producto Eliminado Correctamente.");
            window.location.href = "?action=admin&method=inventario";
            </script>';
        }else{
            echo'<script> alert("Error a Eliminar:'.addslashes($stmt->error).'");
            window.location.href = "?action=admin&method=inventario";
            </script>';
        }

        $stmt->close();
    }
    public function actualizar($id)
    {
        $categorias = $this->db->query("SELECT * FROM categorias");
        if (!$categorias) {
            die("Error al obtener categorías: " . $this->db->error);
        }

        $producto = $this->db->query("SELECT * FROM productos WHERE id = $id");
        if (!$producto) {
            die("Error en la consulta: " . $this->db->error);
        }

        if ($producto->num_rows === 0) {
            header("Location: ?action=admin&method=inventario&error=Producto no encontrado");
            exit();
        }

        $producto = $producto->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_producto = $this->db->real_escape_string($_POST['nombre'] ?? '');
            $descripcion = $this->db->real_escape_string($_POST['descripcion'] ?? '');
            $cantegoria_id = intval($_POST['cantegoria_id'] ?? 0);
            $unidad_medida = $this->db->real_escape_string($_POST['unidad_medida'] ?? '');
            $stock = intval($_POST['stock'] ?? 0);

            if (empty($nombre_producto) || empty($unidad_medida)) {
                $error = "Los campos nombre y unidad de medida son obligatorios";
            } else {
                $sql = "UPDATE productos SET 
                        nombre = '$nombre_producto',
                        descripcion = '$descripcion',
                        cantegoria_id = $cantegoria_id,
                        unidad_medida = '$unidad_medida',
                        stock = $stock
                        WHERE id = $id";
                
                if ($this->db->query($sql)) {
                    header("Location: ?action=admin&method=inventario&success=Producto actualizado correctamente");
                    exit();
                } else {
                    $error = "Error al actualizar: " . $this->db->error;
                }
            }
        }
        return ['productos' => $producto, 'categorias' => $categorias];
    }
    public function reporte($search, $categoria_id, $type)
    {
        echo'<br><br><br><br>' . $categoria_id;

        // Consulta (similar a Index_inventario.php)
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
            $sql .= " AND p.categoria_id = ?";
            $params[] = $categoria_id;
            $types .= 'i';
        }

        $stmt = $this->db->prepare($sql);
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if ($type === 'excel') {
            // Configuración para Excel
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="inventario_'.date('Ymd_His').'.xls"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Ruta absoluta a tu logo (ajusta esta ruta)
            $logoPath = 'assets/img/Logo.jpg';
            
            echo "<html xmlns:x='urn:schemas-microsoft-com:office:excel'>";
            echo "<head>";
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
            echo "<!--[if gte mso 9]>";
            echo "<xml>";
            echo "<x:ExcelWorkbook>";
            echo "<x:ExcelWorksheets>";
            echo "<x:ExcelWorksheet>";
            echo "<x:Name>Inventario</x:Name>";
            echo "<x:WorksheetOptions>";
            echo "<x:DisplayGridlines/>";
            echo "</x:WorksheetOptions>";
            echo "</x:ExcelWorksheet>";
            echo "</x:ExcelWorksheets>";
            echo "</x:ExcelWorkbook>";
            echo "</xml>";
            echo "<![endif]-->";
            echo "<style>";
            echo "td { mso-number-format:\\@; }"; // Para mantener formatos de texto
            echo ".title { font-size: 16pt; font-weight: bold; text-align: center; }";
            echo ".header { background-color: #f2f2f2; font-weight: bold; }";
            echo "</style>";
            echo "</head>";
            echo "<body>";
            
            // Contenedor para el logo y título
            echo "<table width='100%' style='margin-bottom: 20px;'>";
            echo "<tr>";
            
            // Celda con el logo (solo si existe el archivo)
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoInfo = getimagesize($logoPath);
                echo "<td width='100' style='text-align: left; vertical-align: middle;'>";
                echo "<img src='data:".$logoInfo['mime'].";base64,".$logoData."' width='80' alt='Logo'>";
                echo "</td>";
            }
            
            // Celda con el título
            echo "<td style='text-align: center; vertical-align: middle;'>";
            echo "<div class='title'>INVENTARIO DE PRODUCTOS</div>";
            echo "<div style='font-size: 10pt;'>Generado: ".date('d/m/Y H:i:s')."</div>";
            echo "</td>";
            
            // Celda vacía para alineación
            if (file_exists($logoPath)) {
                echo "<td width='100'></td>";
            }
            
            echo "</tr>";
            echo "</table>";
            
            // Tabla de datos
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr class='header'>";
            echo "<th>ID</th>";
            echo "<th>Nombre</th>";
            echo "<th>Descripción</th>";
            echo "<th>Categoría</th>";
            echo "<th>Unidad</th>";
            echo "<th>Stock</th>";
            echo "<th>Fecha Registro</th>";
            echo "</tr>";
            
            foreach ($productos as $p) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($p['id'])."</td>";
                echo "<td>".htmlspecialchars($p['nombre'])."</td>";
                echo "<td>".htmlspecialchars($p['descripcion'])."</td>";
                echo "<td>".htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría')."</td>";
                echo "<td>".htmlspecialchars($p['unidad_medida'])."</td>";
                echo "<td>".htmlspecialchars($p['stock'])."</td>";
                echo "<td>".htmlspecialchars($p['fecha_registro'])."</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            echo "</body></html>";
            exit;

        } elseif ($type === 'pdf') {
            require __DIR__ . '/../vendor/autoload.php';
            
            $html = '<h1 style="text-align:center">Inventario de Productos</h1>
                    <table border="1" cellpadding="5">
                    <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Categoría</th><th>Unidad</th><th>Stock</th><th>Fecha</th></tr>';
            
            foreach ($productos as $p) {
                $html .= "<tr>
                            <td>{$p['id']}</td>
                            <td>{$p['nombre']}</td>
                            <td>{$p['descripcion']}</td>
                            <td>{$p['categoria_nombre']}</td>
                            <td>{$p['unidad_medida']}</td>
                            <td>{$p['stock']}</td>
                            <td>{$p['fecha_registro']}</td>
                        </tr>";
            }
            $html .= "</table>";
            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output('inventario_'.date('Ymd').'.pdf', 'D');
            exit;
        }

        header('Location: Index_inventario.php');
    }
}         
?>
?>