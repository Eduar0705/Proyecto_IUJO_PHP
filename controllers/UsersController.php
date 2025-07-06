<?php
require_once 'model/Conexion.php';
require 'model/Inicio.php';
require 'model/SolicitudModel.php';

class UsersController {
    private $modeloDB;
    private $solicitudModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
            $this->modeloDB = new BaseDatos();
    $this->solicitudModel = new SolicitudModel($this->modeloDB->conectar());

    }
    public function home() {
        $this->modeloDB = new BaseDatos();
        //$this->modeloDB->verificarUsuario();
        $title = "Usuarios";
        require_once 'views/home/users.php';
    }
    
    public function nuevaSolicitud() {
        $this->modeloDB = new BaseDatos();
        $title = "Crear Solicitud";
        require 'views/Usuario/nueva_Solicitud.php';
        eval($this->envioSolicitud());
    }
public function envioSolicitud() {
    // Verificar si es una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit('Método no permitido');
    }

    // Verificar sesión y obtener ID de usuario
    if (!isset($_SESSION['id_usuario'])) {
        header('HTTP/1.1 401 Unauthorized');
        exit('Usuario no autenticado');
    }

    // Inicializar el modelo de solicitud
    $this->solicitudModel = new SolicitudModel($this->modeloDB->conectar());

    // Recoger y sanitizar datos básicos
    $datosSolicitud = [
        'id_usuario' => $_SESSION['id_usuario'],
        'titulo' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING), // Cambiado
        'tipo' => filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING), // Cambiado
        'datos' => []
    ];

    // Validar campos obligatorios
    if (empty($datosSolicitud['titulo']) || empty($datosSolicitud['tipo'])) {
        $_SESSION['error'] = 'El título y el tipo de solicitud son obligatorios';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Procesar datos según el tipo de solicitud
    switch ($datosSolicitud['tipo']) {
        case 'oficina':
            $datosSolicitud['datos'] = $this->procesarSolicitudOficina();
            break;
        case 'comida':
            $datosSolicitud['datos'] = $this->procesarSolicitudComida();
            break;
        case 'proyecto':
            $datosSolicitud['datos'] = $this->procesarSolicitudProyecto();
            break;
        default:
            $_SESSION['error'] = 'Tipo de solicitud no válido';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
    }

    // Intentar crear la solicitud
    try {
        $resultado = $this->solicitudModel->crearSolicitud($datosSolicitud);
        
        if ($resultado) {
            $_SESSION['exito'] = 'Solicitud creada correctamente';
            header('Location: /tu_ruta_de_exito');
        } else {
            $_SESSION['error'] = 'Error al crear la solicitud';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    exit;
}

    private function procesarSolicitudOficina() {
        $productos = [];
        if (isset($_POST['productos']) && is_array($_POST['productos'])) {
            foreach ($_POST['productos'] as $producto) {
                $productos[] = [
                    'nombre' => $producto['nombre'],
                    'cantidad' => $producto['cantidad'],
                    'especificaciones' => $producto['especificaciones']
                ];
            }
        }

        return [
            'solicitante' => 'nombre_solicitante',
            'productos' => $productos,
            'urgencia' => 'urgencia',
            'justificacion' => 'justificacion'
        ];
    }

    private function procesarSolicitudComida() {
        $productos = [];
        if (isset($_POST['productos']) && is_array($_POST['productos'])) {
            foreach ($_POST['productos'] as $producto) {
                $productos[] = [
                    'nombre' => $producto['nombre'],
                    'cantidad' => $producto['cantidad'],
                    'unidad' => $producto['unidad']
                ];
            }
        }

        return [
            'solicitante' => 'nombre_solicitante',
            'productos' => $productos,
            'fecha_entrega' => 'fecha_entrega',
            'comentarios' => 'comentarios',
            'hora_entrega' => 'hora_entrega'
        ];
    }

    private function procesarSolicitudProyecto() {
        return [
            'solicitante' =>'nombre_solicitante', 
            'nombre_proyecto' => 'nombre_proyecto', 
            'fecha_limite' => 'fecha_limite', 
            'presupuesto' => 'presupuesto', 
            'descripcion' => 'descripcion', 
            'recursos' => isset($_POST['recursos']) ? array_map('filter_var', $_POST['recursos']) : [],
            'prioridad' => 'prioridad', 
        ];
    }
}