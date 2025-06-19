<?php
class AboutController {
    public function index() {
        $title = "Sobre Nosotros";
        require_once 'views/layout/header.php';
        require_once 'views/about/index.php';
        require_once 'views/layout/footer.php';
    }
}