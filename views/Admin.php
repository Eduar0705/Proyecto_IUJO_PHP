<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA-Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./img/logo.jpg" type="image/x-icon">
</head>
<?php 
if (isset($_SESSION['nombre'])) {
    $nombre = htmlspecialchars($_SESSION['nombre']);
} else {
    $nombre = "Invitado";
}
?>
<body>
    <header class="admin-header">
        <?php include ('./inc/nav_Admin.php'); ?>
    </header>
    
    <div class="sidebar-container">
        <?php include_once ('./inc/menu_admin.php'); ?>
    </div>
    
    <main class="main-content">
        <h1>Bienvenidos al lado Administrativo</h1>
        <p>Servicios Generales de Invilara.</p>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/menu.js"></script>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>