<?php
/**
 * Clase Inventariado
 * Maneja todas las operaciones relacionadas con el inventario de productos
 */

class Inventariado
{
    private $db;
    private $modeloDB;
    
    public function __construct()
    {
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
        $this->iniciarSesion();
    }

    /**
     * Inicia la sesión si no está activa
     */
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Obtiene el modelo de base de datos
     */
    public function getModeloDB()
    {
        return $this->modeloDB;
    }

    /**
     * Obtiene la conexión de base de datos
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * Refresca y obtiene los datos de productos y categorías con filtros
     */
    public function refrescarDatos()
    {
        try {
            // Validar conexión
            if (!$this->db) {
                throw new Exception("Error de conexión: " . mysqli_connect_error());
            }

            // Obtener parámetros de filtrado
            $parametros = $this->obtenerParametrosFiltrado();

            // Obtener productos filtrados
            $productos = $this->obtenerProductosFiltrados($parametros);

            // Obtener todas las categorías
            $categorias = $this->obtenerCategorias();

            return [
                'productos' => $productos,
                'categorias' => $categorias
            ];

        } catch (Exception $e) {
            error_log("Error en refrescarDatos: " . $e->getMessage());
            return [
                'productos' => [],
                'categorias' => []
            ];
        }
    }

    /**
     * Obtiene los parámetros de filtrado de la URL
     */
    private function obtenerParametrosFiltrado()
    {
        return [
            'search' => $_GET['search'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? ''
        ];
    }

    /**
     * Obtiene productos filtrados según los parámetros
     */
    private function obtenerProductosFiltrados($parametros)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p
                LEFT JOIN categorias c ON p.cantegoria_id = c.id
                WHERE 1=1";

        $params = [];
        $types = '';

        // Aplicar filtro de búsqueda
        if (!empty($parametros['search'])) {
            $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $searchTerm = "%{$parametros['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ss';
        }

        // Aplicar filtro de categoría
        if (!empty($parametros['categoria_id'])) {
            $sql .= " AND p.cantegoria_id = ?";
            $params[] = $parametros['categoria_id'];
            $types .= 'i';
        }

        $sql .= " ORDER BY p.fecha_registro DESC";

        return $this->ejecutarConsultaPreparada($sql, $types, $params);
    }

    /**
     * Obtiene todas las categorías
     */
    private function obtenerCategorias()
    {
        $result = $this->db->query("SELECT * FROM categorias ORDER BY nombre");
        if (!$result) {
            throw new Exception("Error al obtener categorías: " . $this->db->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ejecuta una consulta preparada de forma segura
     */
    private function ejecutarConsultaPreparada($sql, $types, $params)
    {
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->db->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data;
    }

    /**
     * Crea un nuevo producto
     */
    public function crear()
    {
        if (!$this->esMetodoPost()) {
            return;
        }

        try {
            // Validar datos de entrada
            $datosProducto = $this->validarYObtenerDatosProducto();
            if (!$datosProducto) {
                return;
            }

            // Insertar producto
            if ($this->insertarProducto($datosProducto)) {
                $this->redirigirConExito("inventario", "Producto creado correctamente");
            } else {
                $this->mostrarError("Error al crear producto");
            }

        } catch (Exception $e) {
            error_log("Error en crear: " . $e->getMessage());
            $this->mostrarError("Error en el sistema: " . $e->getMessage());
        }
    }

    /**
     * Valida y obtiene los datos del producto desde POST
     */
    private function validarYObtenerDatosProducto()
    {
        $camposRequeridos = ['nombre', 'unidad_medida'];
        
        // Validar campos obligatorios
        foreach ($camposRequeridos as $campo) {
            if (empty(trim($_POST[$campo] ?? ''))) {
                $this->mostrarError("El campo {$campo} es obligatorio");
                return false;
            }
        }

        // Validar y limpiar datos
        return [
            'nombre' => $this->limpiarTexto($_POST['nombre']),
            'descripcion' => $this->limpiarTexto($_POST['descripcion'] ?? ''),
            'cantegoria_id' => $this->validarEntero($_POST['cantegoria_id'] ?? 0),
            'unidad_medida' => $this->limpiarTexto($_POST['unidad_medida']),
            'stock' => $this->validarEntero($_POST['stock'] ?? 0)
        ];
    }

    /**
     * Inserta un nuevo producto en la base de datos
     */
    private function insertarProducto($datos)
    {
        $sql = "INSERT INTO productos (nombre, descripcion, cantegoria_id, unidad_medida, stock) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar inserción: " . $this->db->error);
        }

        $stmt->bind_param("ssisi", 
            $datos['nombre'], 
            $datos['descripcion'], 
            $datos['cantegoria_id'], 
            $datos['unidad_medida'], 
            $datos['stock']
        );

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    /**
     * Elimina un producto por ID
     */
    public function eliminar($id)
    {
        try {
            $id = $this->validarEntero($id);
            if ($id <= 0) {
                throw new Exception("ID inválido");
            }

            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar eliminación: " . $this->db->error);
            }

            $stmt->bind_param("i", $id);
            $resultado = $stmt->execute();
            
            if ($resultado) {
                $this->mostrarExitoYRedirigir("Producto eliminado correctamente", "inventario");
            } else {
                $this->mostrarErrorYRedirigir("Error al eliminar: " . $stmt->error, "inventario");
            }

            $stmt->close();
            return $resultado;

        } catch (Exception $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            $this->mostrarErrorYRedirigir("Error: " . $e->getMessage(), "inventario");
            return false;
        }
    }

    /**
     * Actualiza un producto existente
     */
    public function actualizar($id)
    {
        try {
            $id = $this->validarEntero($id);
            if ($id <= 0) {
                throw new Exception("ID inválido");
            }

            // Obtener datos necesarios
            $categorias = $this->obtenerCategorias();
            $producto = $this->obtenerProductoPorId($id);

            if (!$producto) {
                $this->redirigirConError("inventario", "Producto no encontrado");
                return null;
            }

            // Procesar actualización si es POST
            if ($this->esMetodoPost()) {
                $this->procesarActualizacionProducto($id);
            }

            return [
                'productos' => $producto,
                'categorias' => $categorias
            ];

        } catch (Exception $e) {
            error_log("Error en actualizar: " . $e->getMessage());
            $this->redirigirConError("inventario", "Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene un producto por su ID
     */
    private function obtenerProductoPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar consulta de producto: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();

        return $producto;
    }

    /**
     * Procesa la actualización de un producto
     */
    private function procesarActualizacionProducto($id)
    {
        $datosProducto = $this->validarYObtenerDatosProducto();
        if (!$datosProducto) {
            return;
        }

        $sql = "UPDATE productos SET 
                nombre = ?, 
                descripcion = ?, 
                cantegoria_id = ?, 
                unidad_medida = ?, 
                stock = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar actualización: " . $this->db->error);
        }

        $stmt->bind_param("ssisii", 
            $datosProducto['nombre'], 
            $datosProducto['descripcion'], 
            $datosProducto['cantegoria_id'], 
            $datosProducto['unidad_medida'], 
            $datosProducto['stock'],
            $id
        );

        if ($stmt->execute()) {
            $this->redirigirConExito("inventario", "Producto actualizado correctamente");
        } else {
            throw new Exception("Error al actualizar: " . $stmt->error);
        }

        $stmt->close();
    }

    /*
    public function reporte($search, $categoria_id, $type)
    {
        try {
            // Obtener productos para el reporte
            $productos = $this->obtenerProductosParaReporte($search, $categoria_id);

            // Generar reporte según el tipo
            switch ($type) {
                case 'excel':
                    $this->generarReporteExcel($productos);
                    break;
                case 'pdf':
                    $this->generarReportePDF($productos);
                    break;
                default:
                    $this->redirigirConError("inventario", "Tipo de reporte no válido");
            }

        } catch (Exception $e) {
            error_log("Error en reporte: " . $e->getMessage());
            $this->redirigirConError("inventario", "Error al generar reporte: " . $e->getMessage());
        }
    }*/

    /**
     * Obtiene productos para el reporte con filtros aplicados
     */
    private function obtenerProductosParaReporte($search, $categoria_id)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM productos p
                LEFT JOIN categorias c ON p.cantegoria_id = c.id
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($search)) {
            $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ss';
        }

        if (!empty($categoria_id)) {
            $sql .= " AND p.cantegoria_id = ?";
            $params[] = $categoria_id;
            $types .= 'i';
        }

        $sql .= " ORDER BY p.nombre";

        return $this->ejecutarConsultaPreparada($sql, $types, $params);
    }

    /*
    private function generarReporteExcel($productos)
    {
        // Configurar headers para Excel
        $filename = 'inventario_' . date('Ymd_His') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $this->generarHTMLReporte($productos, 'excel');
        exit;
    }

    private function generarHTMLReporte($productos, $formato)
    {
        if ($formato === 'excel') {
            return $this->generarHTMLExcel($productos);
        }
    }

    Genera HTML específico para Excel
    private function generarHTMLExcel($productos)
    {
        ob_start();
        ?>
        <html xmlns:x='urn:schemas-microsoft-com:office:excel'>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                td { mso-number-format:\@; }
                .title { font-size: 16pt; font-weight: bold; text-align: center; }
                .header { background-color: #f2f2f2; font-weight: bold; }
            </style>
        </head>
        <body>
            <?php $this->renderEncabezadoReporte(); ?>
            <?php $this->renderTablaProductos($productos); ?>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }*/

    /**
     * Renderiza el encabezado del reporte
     */
    private function renderEncabezadoReporte()
    {
        $logoPath = 'assets/img/Logo.jpg';
        ?>
        <table width='100%' style='margin-bottom: 20px;'>
            <tr>
                <?php if (file_exists($logoPath)): ?>
                    <td width='100' style='text-align: left; vertical-align: middle;'>
                        <?php
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoInfo = getimagesize($logoPath);
                        ?>
                        <img src='data:<?= $logoInfo['mime'] ?>;base64,<?= $logoData ?>' width='80' alt='Logo'>
                    </td>
                <?php endif; ?>
                <td style='text-align: center; vertical-align: middle;'>
                    <div class='title'>INVENTARIO DE PRODUCTOS</div>
                    <div style='font-size: 10pt;'>Generado: <?= date('d/m/Y H:i:s') ?></div>
                </td>
                <?php if (file_exists($logoPath)): ?>
                    <td width='100'></td>
                <?php endif; ?>
            </tr>
        </table>
        <?php
    }

    /**
     * Renderiza la tabla de productos
     */
    private function renderTablaProductos($productos, $conClases = true)
    {
        $headerClass = $conClases ? "class='header'" : "";
        ?>
        <table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>
            <tr <?= $headerClass ?>>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Unidad</th>
                <th>Stock</th>
                <th>Fecha Registro</th>
            </tr>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['id']) ?></td>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                    <td><?= htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') ?></td>
                    <td><?= htmlspecialchars($producto['unidad_medida']) ?></td>
                    <td><?= htmlspecialchars($producto['stock']) ?></td>
                    <td><?= htmlspecialchars($producto['fecha_registro']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    // MÉTODOS AUXILIARES

    /**
     * Verifica si la petición es POST
     */
    private function esMetodoPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Limpia texto para evitar XSS
     */
    private function limpiarTexto($texto)
    {
        return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida y convierte a entero
     */
    private function validarEntero($valor)
    {
        return is_numeric($valor) ? intval($valor) : 0;
    }

    /**
     * Muestra mensaje de error
     */
    private function mostrarError($mensaje)
    {
        echo "<script>alert('Error: {$mensaje}');</script>";
    }

    /**
     * Muestra mensaje de éxito y redirige
     */
    private function mostrarExitoYRedirigir($mensaje, $metodo)
    {
        echo "<script>
            alert('{$mensaje}');
            window.location.href = '?action=admin&method={$metodo}';
        </script>";
    }

    /**
     * Muestra mensaje de error y redirige
     */
    private function mostrarErrorYRedirigir($mensaje, $metodo)
    {
        echo "<script>
            alert('Error: {$mensaje}');
            window.location.href = '?action=admin&method={$metodo}';
        </script>";
    }

    /**
     * Redirige con mensaje de error
     */
    private function redirigirConError($metodo, $mensaje)
    {
        header("Location: ?action=admin&method={$metodo}&error=" . urlencode($mensaje));
        exit();
    }

    /**
     * Redirige con mensaje de éxito
     */
    private function redirigirConExito($metodo, $mensaje)
    {
        exit();
    }
}
?>