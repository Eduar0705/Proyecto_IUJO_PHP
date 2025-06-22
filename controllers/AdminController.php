<?php //Reemplazar por el Controlador del MVC de Eduar
class AdminController {
    public function home() {
        $title = "Administracion";
        require_once 'views/home/admin.php';
    }
}
