<?php
require 'model/Conexion.php';
require 'model/Inventariado.php';
class InventarioController {
    private $modelo;
    public $productos;
    public $categorias;
        public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new Inventariado();
    }
    public function refrescarDatos()
    {
        $this->modelo->getModeloDB()->verificarUsuario();
        $datos = $this->modelo->refrescarDatos();
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
    public function crear()
    {
        $this->refrescarDatos();
        $productos = $this->productos;
        $categorias = $this->categorias;
        $title = "Agregar Producto";
        require_once('views/inventario/crear.php');
        $this->modelo->crear();
    }   
}
?>