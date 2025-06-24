<?php
require 'model/Conexion.php';
require 'model/Inventariado.php';
require 'model/Inicio.php';
class AdminController {
    private $modelo;
    private $modelo_inicio;
    private $modeloDB;
    public $productos;
    public $categorias;
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new Inventariado();
    }
    //admin
    public function home() {
        $this->modeloDB = new BaseDatos();
        $this->modeloDB->verificarUsuario();
        $title = "Administracion";
        require_once 'views/home/admin.php';
    }
    public function registroDeUsuarios()
    {
        $title = "Registro de Usuarios";
        $nombre = $_SESSION['nombre'];
        require_once 'views/usuarios/registro.php';
        if(isset($_POST['action']) && isset($_POST['method']))
        {
            if($_POST['method']=='registrarUsuario')
            {
                $this->registrarUsuario();
            }
            
        }
    }
    public function registrarUsuario()
    {
        $base_datos = 'informacion';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $base_datos = 'informacion';
            $names = trim($_POST['nombre']);
            $usuario = trim($_POST['username']);
            $cedula = trim($_POST['CI']);
            $email = trim($_POST['email']);
            if(($_POST['password']) == ($_POST['password2'])) {
                $password = ($_POST['password']);
                $this->modelo_inicio = new Inicio();
                $this->modelo_inicio->registerStore($names, $usuario, $cedula, $email, $password, $base_datos);
            }
            else {
                echo "<script>alert('Por favor, coloque la misma contraseña en ambos campos.');</script>";
            }
        }
    }
    //Inventario
    public function inventario() {
        $categoria_id = $_GET['categoria_id'] ?? '';
        $nombre = $_SESSION['nombre'];
        $title = "Inventario";
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        require_once 'views/inventario/index.php';
    }
    public function refrescarDatos()
    {
        $this->modelo->getModeloDB()->verificarUsuario();
        $datos = $this->modelo->refrescarDatos();
        $this->productos = $datos['productos'];
        $this->categorias = $datos['categorias'];
    }
    public function crear()
    {
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        $title = "Agregar Producto";
        require_once('views/inventario/crear.php');
        $this->modelo->crear();
    }   
    public function eliminar()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id']:0;

        if ($id<=0) {
            die("ID Inválido");
        }
        $this->modelo->eliminar($id);
    }
    public function actualizar()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: ?action=admin&method=inventario&error=ID no válido");
            exit();
        }
        $id = intval($_GET['id']);
        $nombre = $_SESSION['nombre'];
        $datos = $this->modelo->actualizar($id);
        $categorias = $datos['categorias'];
        $producto = $datos['productos'];
        require 'views/inventario/actualizar.php';
    }
    public function reporte()
    {
        $search = $_GET['search'] ?? '';
        $categoria_id = $_GET['categoria_id'] ?? '';
        $type = $_GET['type'] ?? 'excel';
        $this->modelo->reporte($search, $categoria_id, $type);
    }
    //
}
