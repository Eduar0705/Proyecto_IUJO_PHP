<?php
require_once 'model/Conexion.php';
require_once 'model/Inventariado.php';
require_once 'model/Inicio.php';

class AdminController 
{
    private $modelo;
    private $modelo_inicio;
    private $modeloDB;
    public $productos;
    public $categorias;

    public function __construct() 
    {
        $this->iniciarSesion();
        $this->modelo = new Inventariado();
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

    /**
    * Registra un nuevo usuario en el sistema
    */
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
    public function actualizar()
    {
        $this->validarSesion();
        
        $id = $this->validarId($_GET['id'] ?? null);
        if (!$id) {
            $this->redirigirConError("inventario", "ID no válido");
            return;
        }

        try {
            $nombre = $_SESSION['nombre'];
            $datos = $this->modelo->actualizar($id);
            $categorias = $datos['categorias'] ?? [];
            $producto = $datos['productos'] ?? [];
            
            require 'views/inventario/actualizar.php';
        } catch (Exception $e) {
            $this->redirigirConError("inventario", "Error al cargar producto: " . $e->getMessage());
        }
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
        
        // Aquí podrías cargar las solicitudes desde el modelo
        // $solicitudes = $this->modelo->obtenerSolicitudes();
        
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
        
        // Aquí podrías cargar la configuración desde el modelo
        // $configuracion = $this->modelo->obtenerConfiguracion();
        
        require_once 'views/configuracion/index.php';
    }

    //PROYECTOS MENSUALES
    /*
    * Muestra el inicio de proyectos mensuales
    */
    public function proyectos(){
        $this->validarSesion();
        
        $categoria_id = $_GET['categoria_id'] ?? '';
        $nombre = $_SESSION['nombre'];
        $title = "Proyectos Mensuales";
        
        // Aquí podrías cargar los proyectos desde el modelo
        // $proyectos = $this->modelo->obtenerProyectos();
        
        require_once 'views/proyectos/index.php';
    }

    //CONTACTO
    /*
    * Muestra la sección de contacto
    */
    public function contacto(){
        $this->validarSesion();
        
        $nombre = $_SESSION['nombre'];
        $title = "Contacto";
        
        // Aquí podrías cargar la información de contacto desde el modelo
        // $contacto = $this->modelo->obtenerContacto();
        
        require_once 'views/contacto/index.php';
    }
}