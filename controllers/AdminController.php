<?php //Reemplazar por el Controlador del MVC de Eduar
class AdminController {
    public function admin() {
        $title = "Administracion";
        require_once 'views/admin.php';
    }
}
