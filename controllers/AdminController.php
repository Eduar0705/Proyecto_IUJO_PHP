<?php
require 'model/Conexion.php';
class AdminController {
    private $modeloDB;
    public function home() {
        $this->modeloDB = new BaseDatos();
        $this->modeloDB->verificarUsuario();
        $title = "Administracion";
        require_once 'views/home/admin.php';
    }
}
