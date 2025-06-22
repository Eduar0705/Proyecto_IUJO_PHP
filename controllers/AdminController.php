<?php
class AdminController {
    public function home() {
        $title = "Administracion";
        require_once 'views/home/admin.php';
    }
}
