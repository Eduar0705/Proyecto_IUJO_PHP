<?php
require 'model/Conexion.php';
require 'model/Inventariado.php';
class InventarioController {
    private $modeloDB;
    private $db; 
    public $productos;
    public $categorias;
        public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->modeloDB = new BaseDatos();
        $this->db = $this->modeloDB->conectar();
    }
    public function refrescarDatos()
    {
        $this->modeloDB->verificarUsuario();
        $datos = Inventariado::refrescarDatos($this->db);
        $this->productos = $datos['productos'];
        $this->categorias = $datos['categorias'];
    }
    public function inventario() {
        $nombre = $_SESSION['nombre'];
        $title = "Inventario";
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        require_once 'views/inventario/index.php';
    }   
}
?>