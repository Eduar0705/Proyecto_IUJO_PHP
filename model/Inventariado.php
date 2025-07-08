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
    private function mostrarError($mensaje, $tipo = 'error') 
    {
        // Tipos soportados y sus correspondientes estilos Bootstrap
        $tiposPermitidos = [
            'error' => ['color' => 'danger', 'icon' => 'exclamation-triangle-fill', 'title' => 'Error'],
            'success' => ['color' => 'success', 'icon' => 'check-circle-fill', 'title' => 'Éxito'],
            'warning' => ['color' => 'warning', 'icon' => 'exclamation-triangle-fill', 'title' => 'Advertencia'],
            'info' => ['color' => 'info', 'icon' => 'info-circle-fill', 'title' => 'Información'],
            'question' => ['color' => 'primary', 'icon' => 'question-circle-fill', 'title' => 'Confirmación']
        ];
        
        // Validar y establecer tipo por defecto
        $tipo = array_key_exists(strtolower($tipo), $tiposPermitidos) ? strtolower($tipo) : 'error';
        $config = $tiposPermitidos[$tipo];
        
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <div class='position-fixed top-0 end-0 p-3' style='z-index: 1100'>
            <div id='alertToast' class='toast show' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='5000'>
                <div class='toast-header bg-{$config['color']} text-white'>
                    <i class='bi bi-{$config['icon']} me-2'></i>
                    <strong class='me-auto'>{$config['title']}</strong>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='toast' aria-label='Close'></button>
                </div>
                <div class='toast-body'>
                    {$mensaje}
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('alertToast');
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();
                
                // Pausar cuando el mouse entra
                toastEl.addEventListener('mouseenter', function() {
                    toast._config.autohide = false;
                    toast._clearTimeout();
                });
                
                // Reanudar cuando el mouse sale
                toastEl.addEventListener('mouseleave', function() {
                    toast._config.autohide = true;
                    toast._config.delay = 3000; // Tiempo restante reducido
                    toast._setTimeout();
                });
            });
        </script>
        ";
    }

    /**
     * Muestra mensaje de éxito y redirige
     */
    private function mostrarExitoYRedirigir($mensaje, $metodo) 
    {
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <div class='modal fade' id='exitoModal' tabindex='-1' aria-hidden='true' data-bs-backdrop='static'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content border-success'>
                    <div class='modal-header bg-success text-white'>
                        <h5 class='modal-title'>
                            <i class='bi bi-check-circle-fill me-2'></i>
                            ¡Éxito!
                        </h5>
                    </div>
                    <div class='modal-body text-center py-4'>
                        <p class='lead'>{$mensaje}</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-success' onclick='redirigir()'>
                            <i class='bi bi-check-lg me-2'></i>
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Mostrar modal automáticamente
            document.addEventListener('DOMContentLoaded', function() {
                var exitoModal = new bootstrap.Modal(document.getElementById('exitoModal'));
                exitoModal.show();
            });
            
            // Función para redireccionar
            function redirigir() {
                window.location.href = '?action=admin&method={$metodo}';
            }
            
            // Redireccionar automáticamente después de 5 segundos
            setTimeout(redirigir, 5000);
        </script>
        ";
    }

    /**
     * Muestra mensaje de error y redirige
     */
    private function mostrarErrorYRedirigir($mensaje, $metodo) 
    {
        echo "
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <!-- Bootstrap Icons -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
        <!-- Bootstrap JS Bundle con Popper -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>

        <div class='modal fade' id='errorModal' tabindex='-1' aria-hidden='true' data-bs-backdrop='static'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content border-danger'>
                    <div class='modal-header bg-danger text-white'>
                        <h5 class='modal-title'>
                            <i class='bi bi-exclamation-triangle-fill me-2'></i>
                            ¡Error!
                        </h5>
                    </div>
                    <div class='modal-body text-center py-4'>
                        <p class='lead'>{$mensaje}</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' onclick='redirigir()'>
                            <i class='bi bi-arrow-right-circle me-2'></i>
                            Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Mostrar modal automáticamente
            document.addEventListener('DOMContentLoaded', function() {
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            });
            
            // Función para redireccionar
            function redirigir() {
                window.location.href = '?action=admin&method={$metodo}';
            }
            
            // Redireccionar automáticamente después de 5 segundos
            setTimeout(redirigir, 5000);
        </script>
        ";
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