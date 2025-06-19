<?php
class ContactController {
    public function index() {
        $title = "Contáctanos";
        require_once 'views/layout/header.php';
        require_once 'views/contact/index.php';
        require_once 'views/layout/footer.php';
    }
}