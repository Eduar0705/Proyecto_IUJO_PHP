<?php
require 'model/Conexion.php';
require 'model/Inicio.php';
require 'model/SolicitudModel.php';

class UsersController {
    private $modeloDB;
    private $solicitudModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function home() {
        $this->modeloDB = new BaseDatos();
        //$this->modeloDB->verificarUsuario();
        $title = "Dashboard";
        require_once 'views/home/users.php';
    }
    

public function nuevaSolicitud() {
    $error = null;
    $datosEspecificos = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['id_usuario'])) {
            die("Usuario no autenticado");
        }

        // Datos básicos
        $titulo = trim($_POST['titulo'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $idUsuario = $_SESSION['id_usuario'];
        
        // Procesamiento según el tipo de solicitud
        switch ($tipo) {
            case 'Oficina':
                $productos = trim($_POST['productos'] ?? '');
                $urgencia = trim($_POST['urgencia'] ?? '');
                $datosEspecificos = [
                    'productos' => $productos,
                    'urgencia' => $urgencia
                ];
                break;
                
            case 'comida':
                $productos = [];
                if (isset($_POST['productos']) && is_array($_POST['productos'])) {
                    foreach ($_POST['productos'] as $producto) {
                        if (!empty($producto['nombre'])) {
                            $productos[] = [
                                'nombre' => trim($producto['nombre']),
                                'cantidad' => (float)($producto['cantidad'] ?? 0),
                                'unidad' => trim($producto['unidad'] ?? '')
                            ];
                        }
                    }
                }
                $datosEspecificos = [
                    'productos' => $productos,
                    'fecha_entrega' => $_POST['fecha_entrega'] ?? null,
                    'comentarios' => trim($_POST['comentarios'] ?? '')
                ];
                break;
                
            case 'Proyecto':
                $datosEspecificos = [
                    'nombre_proyecto' => trim($_POST['nombre_proyecto'] ?? ''),
                    'fecha_limite' => trim($_POST['fecha_limite'] ?? ''),
                    'presupuesto' => (float)($_POST['presupuesto'] ?? 0),
                    'descripcion_proyecto' => trim($_POST['descripcion_proyecto'] ?? '')
                ];
                break;
        }
        
        // Validación
        if (empty($titulo)) {
            $error = "El título es obligatorio";
        } elseif (empty($tipo)) {
            $error = "Debe seleccionar un tipo de solicitud";
        } 
        
        if (!$error) {
            // Verificar que el modelo está instanciado
            if (!$this->solicitudModel) {
                //$this->solicitudModel = new SolicitudModel();
            }
            
            $resultado = $this->solicitudModel->crearSolicitud([
                'id_informacion' => $idUsuario, // Usamos id_informacion que es el campo en tu tabla
                'titulo' => $titulo,
                'tipo' => $tipo,
                'datos' => json_encode($datosEspecificos)
            ]);
            
            if ($resultado) {
                header("Location: ?action=users&method=misSolicitudes&success=1");
                exit();
            } else {
                $error = "Error al guardar la solicitud";
            }
        }
    }
    require 'views/layout/header_User.php';
    require 'views/Usuario/nueva_Solicitud.php';
}
}