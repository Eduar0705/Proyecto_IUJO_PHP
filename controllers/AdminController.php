<?php
require_once 'model/Conexion.php';
require_once 'model/Inventariado.php';
require_once 'model/Inicio.php';
require_once 'model/contacto.php';
require_once 'model/ProyectoModel.php';
require_once 'model/config.php';
require_once 'model/SolicitudModel.php';

class AdminController 
{
    private $modelo;
    private $modelo_inicio;
    private $modeloDB;
    public $productos;
    public $categorias;
    public $model;
    private $config;
    private $solicitudModel;

    public function __construct() 
    {
        $this->iniciarSesion();
        $this->modelo = new Inventariado();
        $this->model = new ContactoModel();
        $this->config = new Configuracion();
        $this->solicitudModel = new SolicitudModel($this->modeloDB);
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
    * Valida que el usuario esté logueado
    */
    private function validarSesion()
    {
        if (!isset($_SESSION['nombre'])) {
            header("Location: ?action=login");
            exit();
        }
    }

    /**
    * Página principal de administración
    */
    public function home() 
    {
        $this->validarSesion();
        $this->modeloDB = new BaseDatos();
        
        $title = "Administración";
        require_once 'views/home/admin.php';
    }

    /**
    * Muestra el formulario de registro de usuarios
    */
    public function registroDeUsuarios()
    {
        $this->validarSesion();
        
        $title = "Registro de Usuarios";
        $nombre = $_SESSION['nombre'];
        
        // Procesar formulario si se envió
        if ($this->esPostValido(['action', 'method']) && $_POST['method'] === 'registrarUsuario') {
            $this->registrarUsuario();
        }
        
        require_once 'views/usuarios/registro.php';
    }
    public function solicitudesUsuario()
    {
        $this->validarSesion();
        $title = "Solicitudes de Cuenta";
        $this->validarSesion();
        
        $nombre = $_SESSION['nombre'];
        $res = $this->config->buscarSolicitud();
        require_once 'views/usuarios/solicitudes_registro.php';
    }

    /**
    * Registra un nuevo usuario en el sistema
    */
    public function validarSolicitud(){
        if(!isset($this->modeloDB))
        {
            $this->modeloDB = new BaseDatos();
        }
        $id = $_GET['id'];
        $conex = $this->modeloDB->conectar();
        
        $sql = "SELECT * FROM solicitud_registro WHERE id = {$id}";
        $resultado = $conex->query($sql);
        $found_user = mysqli_fetch_assoc($resultado);
        
        if($found_user) {
            $nombre = $found_user['nombre'];
            $usuario = $found_user['usuario'];
            $clave = $found_user['clave'];
            $email = $found_user['email'];
            $cedula = $found_user['cedula'];
            
            if($this->insertarNuevoUsuario('informacion', $nombre, $usuario, $clave, $email, $cedula, $conex))
            {
                // Elimina la solicitud cuando se agg correctamente
                $sql_delete = "DELETE FROM solicitud_registro WHERE id = {$id}";
                $conex->query($sql_delete);
                
                // Mostrar mensaje y redirigir después de 5 segundos
                echo '<script>
                        setTimeout(function() {
                            window.location.href = "?action=admin&method=configuracion";
                        }, 2000);
                    </script>';
                $this->cargandoVisual(5);
                exit;
            }
        }
        
        // Si algo falló, redirige 
        header("Location:?action=admin&method=solicitudesUsuario");
        exit;
    }
    //Pagina de cargado chill jejeje
    public function cargandoVisual($duracion = 5) {
        // Configuración inicial para streaming
        header('Content-Type: text/html; charset=utf-8');
        ob_implicit_flush(true);
        ob_end_flush();
        
        // HTML inicial con estilos CSS
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Cargando...</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f5f5;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .loader-container {
                    text-align: center;
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }
                .loader {
                    display: inline-block;
                    width: 50px;
                    height: 50px;
                    border: 5px solid #f3f3f3;
                    border-top: 5px solid #3498db;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-bottom: 20px;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .progress-bar {
                    width: 100%;
                    height: 10px;
                    background: #e0e0e0;
                    border-radius: 5px;
                    margin-top: 20px;
                    overflow: hidden;
                }
                .progress {
                    height: 100%;
                    background: #3498db;
                    width: 0%;
                    transition: width 0.3s;
                }
            </style>
        </head>
        <body>
            <div class="loader-container">
                <div class="loader"></div>
                <h2>Procesando, por favor espere...</h2>
                <div class="progress-bar">
                    <div class="progress" id="progress"></div>
                </div>
                <p id="percentage">0%</p>
            </div>
        </body>
        </html>';

        // JavaScript para actualizar la barra de progreso
        echo '<script>
            var progress = 0;
            var interval = setInterval(function() {
                progress += ' . (100/$duracion) . ';
                document.getElementById("progress").style.width = progress + "%";
                document.getElementById("percentage").innerHTML = Math.min(progress, 100) + "%";
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(function() {
                        document.querySelector(".loader-container").innerHTML = \'<h2 style="color:#27ae60;">¡Proceso completado!</h2>\';
                    }, 500);
                }
            }, 1000);
        </script>';
        
        // Tiempo de procesamiento en PHP (simulado)
        sleep($duracion);
    }

    public function eliminarSolicitud($id = null) {
        // Si no recibe el parámetro directamente, lo obtenemos de $_GET
        if ($id === null) {
            $id = $_GET['id'] ?? null;
        }

        // Validamos que el ID exista y sea numérico
        if (!$id || !is_numeric($id)) {
            $_SESSION['mensaje'] = "ID inválido o no proporcionado";
            $_SESSION['tipo_mensaje'] = "danger";
            header("Location: ?action=admin&method=solicitudesUsuario");
            exit;
        }

        try {
            if(!isset($this->modeloDB)){
                $this->modeloDB = new BaseDatos();
            }
            
            $sql = "DELETE FROM solicitud_registro WHERE id = ?";
            $conex = $this->modeloDB->conectar();
            $stmt = $conex->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if($stmt->affected_rows > 0) {
                $_SESSION['mensaje'] = "Solicitud eliminada correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "No se encontró la solicitud con ID $id";
                $_SESSION['tipo_mensaje'] = "warning";
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al eliminar la solicitud: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        header("Location: ?action=admin&method=solicitudesUsuario");
        exit;
    }
    public function insertarNuevoUsuario($tabla, $nombre, $usuario, $password, $email, $cedula, $db)
    {
        $consulta = "INSERT INTO {$tabla} (nombre, usuario, clave, email, cedula, id_cargo) VALUES (?, ?, ?, ?, ?, 2)";
        $stmt = $db->prepare($consulta);
        
        if (!$stmt) {
            throw new Exception("Error preparando inserción: " . mysqli_error($db));
        }

        $stmt->bind_param("sssss", $nombre, $usuario, $password, $email, $cedula);
        $resultado = $stmt->execute();

        if ($resultado) {
            $mensaje = ($tabla === 'solicitud_registro') 
                ? "Solicitud de registro enviada" 
                : "Nuevo usuario registrado!";
            $this->mostrarExito($mensaje);
            $stmt->close();
            return true;
        } else {
            $error = "Error al registrar usuario: " . mysqli_error($db);
            $this->mostrarError("Error al registrar usuario: {$error}");
            $stmt->close();
            return false;
        }
    }
    public function registrarUsuario()
    {
        if (!$this->esMetodoPost()) {
            $this->mostrarError("Método no permitido");
            return;
        }

        // Validar campos requeridos
        $camposRequeridos = ['nombre', 'username', 'CI', 'email', 'password', 'password2'];
        if (!$this->validarCamposRequeridos($camposRequeridos)) {
            $this->mostrarError("Todos los campos son obligatorios");
            return;
        }

        // Limpiar y obtener datos
        $datos = $this->obtenerDatosLimpios([
            'nombre' => 'nombre',
            'usuario' => 'username', 
            'cedula' => 'CI',
            'email' => 'email'
        ]);

        // Validar que las contraseñas coincidan
        if ($_POST['password'] !== $_POST['password2']) {
            $this->mostrarError("Las contraseñas no coinciden");
            return;
        }

        // Registrar usuario
        try {
            $this->modelo_inicio = new Inicio();
            $resultado = $this->modelo_inicio->registerStore(
                $datos['nombre'], 
                $datos['usuario'], 
                $datos['cedula'], 
                $datos['email'], 
                $_POST['password'], 
                'informacion'
            );

            if ($resultado) {
                $this->mostrarExito("Usuario registrado correctamente");
            } else {
                $this->mostrarError("Error al registrar usuario");
            }
        } catch (Exception $e) {
            $this->mostrarError("Error en el sistema: " . $e->getMessage());
        }
    }

    /**
    * Muestra la página de inventario
    */
    public function inventario() 
    {
        $this->validarSesion();
        
        $categoria_id = $_GET['categoria_id'] ?? '';
        $nombre = $_SESSION['nombre'];
        $title = "Inventario";
        
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        
        require_once 'views/inventario/index.php';
    }

    /**
    * Actualiza los datos de productos y categorías
    */
    public function refrescarDatos()
    {
        try {
            $datos = $this->modelo->refrescarDatos();
            $this->productos = $datos['productos'] ?? [];
            $this->categorias = $datos['categorias'] ?? [];
        } catch (Exception $e) {
            $this->mostrarError("Error al cargar datos: " . $e->getMessage());
            $this->productos = [];
            $this->categorias = [];
        }
    }

    /**
    * Muestra el formulario para crear producto
    */
    public function crear()
    {
        $this->validarSesion();
        
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        $title = "Agregar Producto";
        
        require_once 'views/inventario/crear.php';
        
        // Procesar creación si se envió el formulario
        $this->modelo->crear();
    }   

    /**
    * Elimina un producto por ID
    */
    public function eliminar()
    {
        $this->validarSesion();
        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("inventario", "ID inválido");
            return;
        }

        try {
            $resultado = $this->modelo->eliminar($id);
            if ($resultado) {
                $this->redirigirConExito("inventario", "Producto eliminado correctamente");
            } else {
                $this->redirigirConError("inventario", "Error al eliminar producto");
            }
        } catch (Exception $e) {
            $this->redirigirConError("inventario", "Error: " . $e->getMessage());
        }
    }

    /**
    * Muestra el formulario para actualizar producto
    */
    public function actualizar(){
        $this->validarSesion();
        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("inventario", "ID no válido");
            return;
        }

        try {
            $nombre = $_SESSION['nombre'];
            $this->modelo = new Inventariado(); // Asegurar instancia
            $datos = $this->modelo->actualizar($id);
            
            // Mantener como objetos MySQLi result
            $categorias = $datos['categorias'];
            $producto = $datos['productos'];
            require 'views/inventario/actualizar.php';
        } catch (Exception $e) {
            $this->redirigirConError("inventario", "Error al cargar producto: " . $e->getMessage());
        }
    }
    public function actualizarSubir()
    {
        $this->actualizar();
        require 'views/inventario/index.php';
    }
    /**
    * Genera reporte de inventario
    */
    public function reporte()
    {
        $this->validarSesion();
        
        $parametros = [
            'search' => $_GET['search'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'type' => $_GET['type'] ?? 'excel'
        ];

        /*try {
            $this->modelo->reporte(
                $parametros['search'], 
                $parametros['categoria_id'], 
                $parametros['type']
            );
        } catch (Exception $e) {
            $this->mostrarError("Error al generar reporte: " . $e->getMessage());
        }*/
    }

    // MÉTODOS AUXILIARES

    /**
    * Valida si la petición es POST
    */
    private function esMetodoPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
    * Valida si existen los campos POST requeridos
    */
    private function esPostValido(array $campos): bool
    {
        if (!$this->esMetodoPost()) return false;
        
        foreach ($campos as $campo) {
            if (!isset($_POST[$campo])) return false;
        }
        return true;
    }

    /**
    * Valida que todos los campos requeridos estén presentes
    */
    private function validarCamposRequeridos(array $campos): bool
    {
        foreach ($campos as $campo) {
            if (empty(trim($_POST[$campo] ?? ''))) {
                return false;
            }
        }
        return true;
    }

    /**
    * Obtiene datos limpios del POST
    */
    private function obtenerDatosLimpios(array $mapeo): array
    {
        $datos = [];
        foreach ($mapeo as $clave => $campoPost) {
            $datos[$clave] = trim($_POST[$campoPost] ?? '');
        }
        return $datos;
    }

    /**
    * Valida y convierte ID a entero
    */
    private function validarId($id): int
    {
        if (!isset($id) || !is_numeric($id)) {
            return 0;
        }
        
        $idNumerico = intval($id);
        return $idNumerico > 0 ? $idNumerico : 0;
    }

    /**
    * Muestra mensaje de error
    */
    private function mostrarError(string $mensaje)
    {
        echo "<script>alert('Error: {$mensaje}');</script>";
    }

    /**
    * Muestra mensaje de éxito
    */
    private function mostrarExito(string $mensaje)
    {
        echo "<script>alert('Éxito: {$mensaje}');</script>";
    }

    /**
    * Redirecciona con mensaje de error
    */
    private function redirigirConError(string $metodo, string $mensaje)
    {
        header("Location: ?action=admin&method={$metodo}&error=" . urlencode($mensaje));
        exit();
    }

    /**
    * Redirecciona con mensaje de éxito
    */
    private function redirigirConExito(string $metodo, string $mensaje)
    {
        header("Location: ?action=admin&method={$metodo}&success=" . urlencode($mensaje));
        exit();
    }

    //SOLICITUDES 
    /*
    *Miestra el inicio de solicitudes
    */
    public function solicitudes(){
        $this->validarSesion();
        
        $categoria_id = $_GET['categoria_id'] ?? '';
        $nombre = $_SESSION['nombre'];
        $title = "Solicitudes";
        
        require_once 'views/solicitudes/index.php';
    }


    //COFIGURACIÓN 
    /*
    * Muestra la configuración del administrador
    */
    public function configuracion(){
        $this->validarSesion();
        
        $nombre = $_SESSION['nombre'];
        $title = "Configuración";
        
        if (isset($_POST['actualizar'])) {
            $id       = $_POST['id_usuario'] ?? '';
            $nombre   = $_POST['nombre'] ?? '';
            $usuario  = $_POST['usuario'] ?? '';
            $email    = $_POST['email'] ?? '';
            $cedula   = $_POST['cedula'] ?? '';
            $cargo    = $_POST['cargo'] ?? '';

            try {
                $this->config->actualizarUsuario($id, $nombre, $usuario, $email, $cedula, $cargo);
                echo "<script>alert('Usuario actualizado con éxito'); window.location.href='?action=admin&method=configuracion';</script>";
                exit;
            } catch (Exception $e) {
                echo "Error al actualizar usuario: " . $e->getMessage();
            }
        }

        $res = $this->config->buscarUsuarios();
        require_once 'views/configuracion/index.php';
    }


    public function eliminarUsuario()
    {
        $this->validarSesion();
        
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $this->redirigirConError("configuracion", "ID no válido");
            return;
        }

        try {
            $resultado = $this->config->eliminarUsuario($id);
            if ($resultado) {
                $this->redirigirConExito("configuracion", "Usuario eliminado correctamente");
            } else {
                $this->redirigirConError("configuracion", "Error al eliminar usuario");
            }
        } catch (Exception $e) {
            $this->redirigirConError("configuracion", "Error: " . $e->getMessage());
        }
    }

    //PROYECTOS MENSUALES
    /*
    * Muestra el inicio de proyectos mensuales
    */
    public function proyectos() {
    $this->validarSesion();
    
    $nombre = $_SESSION['nombre'] ?? 'Usuario';
    $title = "Proyectos Mensuales";
    
    try {
        $modeloProyectos = new ProyectoModel();
        
        // Procesar operaciones CRUD si existen
        if (isset($_GET['eliminar'])) {
            $this->eliminarProyecto($modeloProyectos);
        }
        
        // Obtener datos para la vista
        $busqueda = $_GET['busqueda'] ?? '';
        $proyectos = $modeloProyectos->obtenerTodos($busqueda);
        $estadisticas = $modeloProyectos->obtenerEstadisticas();
        
        require_once 'views/proyectos/index.php';
        
    } catch (Exception $e) {
        $this->redirigirConError("proyectos", $e->getMessage());
    }
}

/**
 * Muestra el formulario para crear/editar proyecto
 */
    public function gestionarProyecto() {
    $this->validarSesion();
    
    $id = $this->validarId($_GET['id'] ?? null);
    $title = $id ? "Editar Proyecto" : "Nuevo Proyecto";
    $nombre = $_SESSION['nombre'] ?? 'Usuario'; // ← AÑADE ESTA LÍNEA
    
    try {
        $modeloProyectos = new ProyectoModel();
        $proyecto = $id ? $modeloProyectos->obtenerPorId($id) : null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardarProyecto($modeloProyectos, $id);
        }
        
        require_once 'views/proyectos/formulario.php';
        
    } catch (Exception $e) {
        $this->redirigirConError("proyectos", $e->getMessage());
    }
}

/**
 * Guarda un proyecto (creación o actualización)
 */
private function guardarProyecto($modelo, $id = null) {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
        'fecha_fin' => $_POST['fecha_fin'] ?? '',
        'estado' => $_POST['estado'] ?? 'planificado'
    ];
    
    // Validaciones básicas
    if (empty($datos['nombre'])) {
        throw new Exception("El nombre del proyecto es obligatorio");
    }
    
    if (empty($datos['fecha_inicio'])) {
        throw new Exception("La fecha de inicio es obligatoria");
    }
    
    if ($id) {
        $resultado = $modelo->actualizar($id, $datos);
        $mensaje = "Proyecto actualizado correctamente";
    } else {
        $resultado = $modelo->crear($datos);
        $mensaje = "Proyecto creado correctamente";
    }
    
    $this->redirigirConExito("proyectos", $mensaje);
}

/**
 * Elimina un proyecto
 */
private function eliminarProyecto($modelo) {
    $id = $this->validarId($_GET['eliminar']);
    if (!$id) {
        throw new Exception("ID de proyecto no válido");
    }
    
    $resultado = $modelo->eliminar($id);
    
    if ($resultado) {
        $this->redirigirConExito("proyectos", "Proyecto eliminado correctamente");
    } else {
        throw new Exception("No se pudo eliminar el proyecto");
    }
}

    //CONTACTO
    /*
    * Muestra la sección de contacto
    */
    public function Contacto() {
    
        $this->validarSesion();
        
        $busqueda = $_GET['busqueda'] ?? '';
        $title = "Gestión de Contactos";
        $nombre = $_SESSION['nombre'];
        
        try {
            $contactos = $this->model->obtenerTodos($busqueda);
            
            if (empty($contactos)) {
                $_SESSION['warning'] = "No se encontraron contactos";
            }
            
            require_once 'views/contacto/index.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar contactos: " . $e->getMessage();
            header("Location: ?action=admin&method=home");
            exit();
        }
    }

    /**
    * Crea un nuevo contacto
    */
    public function crearContacto() {
        $this->validarSesion();
        
        $title = "Agregar Contacto";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['nombre'])) {
                    throw new Exception("El nombre es obligatorio");
                }
                
                if (empty($_POST['email'])) {
                    throw new Exception("El email es obligatorio");
                }

                $resultado = $this->model->crearContacto($_POST);
                
                if ($resultado) {
                    $this->redirigirConExito("contacto", "Contacto creado correctamente");
                } else {
                    throw new Exception("No se pudo crear el contacto");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once 'views/contacto/crear.php';
            }
        } else {
            require_once 'views/contacto/crear.php';
        }
    }

    /**
    * Actualiza un contacto existente
    */
    public function actualizarContacto() {
        $this->validarSesion();
        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("contacto", "ID de contacto no válido");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $resultado = $this->model->actualizarContacto($id, $_POST);
                
                if ($resultado) {
                    $this->redirigirConExito("contacto", "Contacto actualizado correctamente");
                } else {
                    throw new Exception("No se pudo actualizar el contacto");
                }
            } catch (Exception $e) {
                $this->redirigirConError("contacto", $e->getMessage());
            }
        }

        try {
            $contacto = $this->model->obtenerContactoPorId($id);
            if (!$contacto) {
                throw new Exception("Contacto no encontrado");
            }

            $title = "Editar Contacto";
            require_once 'views/contacto/editar.php';
        } catch (Exception $e) {
            $this->redirigirConError("contacto", $e->getMessage());
        }
    }

    /**
    * Elimina un contacto
    */
    public function eliminarContacto() {
        $this->validarSesion();
        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("contacto", "ID de contacto no válido");
            return;
        }

        try {
            $contacto = $this->model->obtenerContactoPorId($id);
            if (!$contacto) {
                throw new Exception("Contacto no encontrado");
            }

            $resultado = $this->model->eliminarContacto($id);
            
            if ($resultado) {
                $this->redirigirConExito("contacto", "Contacto eliminado correctamente");
            } else {
                throw new Exception("No se pudo eliminar el contacto");
            }
        } catch (Exception $e) {
            $this->redirigirConError("contacto", $e->getMessage());
        }
    }
 public function gestionSolicitudes() {
        $this->validarSesion();
        
        $filtro = $_GET['filtro'] ?? null;
        $solicitudes = $this->solicitudModel->obtenerTodasSolicitudes($filtro);
        $title = "Gestión de Solicitudes";
        
        require_once 'views/admin/solicitudes/listado.php';
    }

    /**
     * Muestra el detalle de una solicitud para aprobar/rechazar
     */
    public function detalleSolicitud() {
        $this->validarSesion();

        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("gestionSolicitudes", "ID no válido");
        }
        
        $solicitud = $this->solicitudModel->obtenerSolicitudPorId($id);
        if (!$solicitud) {
            $this->redirigirConError("gestionSolicitudes", "Solicitud no encontrada");
        }
        
        $title = "Solicitud #" . $solicitud['id'];
        require_once 'views/admin/solicitudes/detalle.php';
    }

    /**
     * Procesa la aprobación/rechazo de una solicitud
     */
    public function procesarSolicitud() {
        $this->validarSesion();
        
        if (!$this->esMetodoPost()) {
            $this->redirigirConError("gestionSolicitudes", "Método no permitido");
        }
        
        $id = $this->validarId($_POST['id'] ?? null);
        $accion = $_POST['accion'] ?? '';
        $comentario = $_POST['comentario'] ?? null;
        
        if (!$id || !in_array($accion, ['aprobar', 'rechazar'])) {
            $this->redirigirConError("gestionSolicitudes", "Datos inválidos");
        }
        
        $estado = ($accion === 'aprobar') ? 'Aprobada' : 'Rechazada';
        
        try {
            $resultado = $this->solicitudModel->actualizarEstadoSolicitud(
                $id, 
                $estado, 
                $comentario
            );
            
            if ($resultado) {
                $this->redirigirConExito("gestionSolicitudes", "Solicitud {$estado} correctamente");
            } else {
                $this->redirigirConError("gestionSolicitudes", "Error al procesar solicitud");
            }
        } catch (Exception $e) {
            $this->redirigirConError("gestionSolicitudes", $e->getMessage());
        }
    }


}
