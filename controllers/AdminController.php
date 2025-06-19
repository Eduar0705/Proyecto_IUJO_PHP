<?php
class AdminController {
    public function admin() {
        $title = "Administracion";
        require_once 'views/admin.php';
    }
}