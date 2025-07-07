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

    $this->solicitudModel = new SolicitudModel($this->modeloDB->conectar());

    // Validar existencia de campos
    if (!isset($_POST['nombre'], $_POST['titulo'], $_POST['tipoSolicitud'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan campos obligatorios'
        ]);
        exit;
    }

    $datosSolicitud = [
        'solicitante' => $_POST['nombre'],
        'titulo' => $_POST['titulo'],
        'tipo' => $_POST['tipoSolicitud'],
        'datos' => []
    ];

    // Calcular fecha límite
    if ($datosSolicitud['tipo'] !== 'oficina') {
        $datosSolicitud['fecha_limite'] = $_POST['fecha_limite'] ?? null;
    } else {
        $urgencia = $_POST['urgencia'] ?? 'normal';
        $fecha = new DateTime();

        switch ($urgencia) {
            case 'urgente': $fecha->modify('+2 days'); break;
            case 'muy_urgente': $fecha->modify('+1 days'); break;
            default: $fecha->modify('+10 days');
        }
        $datosSolicitud['fecha_limite'] = $fecha->format('Y-m-d');
    }

    // Procesar tipo
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
            echo json_encode([
                'success' => false,
                'message' => 'Tipo de solicitud no válido'
            ]);
            exit;
    }

    // Guardar
    try {
        $resultado = $this->solicitudModel->crearSolicitud($datosSolicitud);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'redirect' => '?action=users&method=nuevaSolicitud'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la solicitud'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Excepción: ' . $e->getMessage()
        ]);
    }

    exit;
}

    private function procesarSolicitudOficina() {
        return [
            'productos' => $_POST['productos'],
            'urgencia' => $_POST['urgencia'],
            'justificacion' => $_POST['justificacion']
        ];
    }

    private function procesarSolicitudComida() {
    $productos = [];
    if (isset($_POST['productos']) && is_array($_POST['productos'])) {
        foreach ($_POST['productos'] as $producto) {
            $productos[] = [
                'producto' => filter_var($producto['nombre'], FILTER_SANITIZE_STRING),
                'cantidad' => floatval($producto['cantidad']),
                'unidad' => filter_var($producto['unidad'], FILTER_SANITIZE_STRING)
            ];
        }
        $productos['comentarios'] = $_POST['comentarios'];
    }

    return $productos;
}


    private function procesarSolicitudProyecto() {
        return [
            'nombre_proyecto' => $_POST['nombre_proyecto'], 
            'presupuesto' => $_POST['presupuesto'], 
            'descripcion' => $_POST['descripcion_proyecto'], 
            'recursos' => $_POST['recursos'],
            'prioridad' => $_POST['prioridad']
        ];
    }
}