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
        $num_solicitudes = $this->solicitudModel->cantSolicitudesPropias($_SESSION['id']);
        $this->modeloDB = new BaseDatos();
        $title = "Usuarios";
        require_once 'views/home/users.php';
    }
    
    public function nuevaSolicitud() {
        $this->modeloDB = new BaseDatos();
        $title = "Crear Solicitud";
        require 'views/Usuario/nueva_Solicitud.php';
    }
    public function envioSolicitud() {
        $this->solicitudModel = new SolicitudModel($this->modeloDB->conectar());

        $datosSolicitud = [
            'solicitante' => $_POST['nombre'],
            'id_solicitante' => $_SESSION['id'],
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
                $this->mostrarError("Tipo de solicitud no válido");
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
            $this->mostrarError($e->getMessage());
        }
        exit;
    }

    private function procesarSolicitudOficina() {
        $productos = [];
        if (isset($_POST['productos']) && is_array($_POST['productos'])) {
            foreach ($_POST['productos'] as $producto) {
                $productos[] = [
                    'producto' => filter_var($producto['nombre']),
                    'cantidad' => floatval($producto['cantidad']),
                    'unidad' => filter_var($producto['unidad'])
                ];
            }
            $productos['justificacion'] = $_POST['justificacion'];
        }
        $productos['urgencia'] = $_POST['urgencia'];
        return $productos;
}
    private function procesarSolicitudComida() {
    $productos = [];
    if (isset($_POST['productos']) && is_array($_POST['productos'])) {
        foreach ($_POST['productos'] as $producto) {
            $productos[] = [
                'producto' => filter_var($producto['nombre']),
                'cantidad' => floatval($producto['cantidad']),
                'unidad' => filter_var($producto['unidad'])
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
    public function historial()
    {
        $title = "Crear Solicitud";
        $id = $_SESSION['id'];
        $res = $this->solicitudModel->solicitudesPropias($id);
        require 'views/Usuario/historial.php';
    }
}