<?php
require 'model/conexion.php';
require 'model/Inventariado.php';
class InventarioController {
    private $db; 
    public $productos;
    public $categorias;
        public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = BaseDatos::conectar();
    }
    public function verificarUsuario()
    {
        $nombre = "Invitado";
        if (!empty($_SESSION['nombre']) && is_string($_SESSION['nombre'])) {
            $nombre = htmlspecialchars(trim($_SESSION['nombre']), ENT_QUOTES, 'UTF-8');
    }
    $es_admin = $_SESSION['id_cargo'];
    if ($es_admin!==1)
    {
        echo '<br> wut';
        header('Location: ?action=inicio');
        exit();
    }
    return $nombre;
    }
    public function refrescarDatos()
    {
        $this->verificarUsuario();
        $datos = Inventariado::refrescarDatos($this->db);
        $this->productos = $datos['productos'];
        $this->categorias = $datos['categorias'];
    }
    public function inventario() {
        $nombre = $this->verificarUsuario();
        $title = "Inventario";
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        require_once 'views/inventario/index.php';

    }   
}
?>