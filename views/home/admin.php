<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre = "Invitado";
if (!empty($_SESSION['nombre']) && is_string($_SESSION['nombre'])) {
    $nombre = htmlspecialchars(trim($_SESSION['nombre']), ENT_QUOTES, 'UTF-8');
}
$es_admin = isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == 1;
?>
<body>
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>
    
    <main class="main-content">
            <div class="welcome-image-container">
                <img src="./assets/img/Sunset.png" alt="Bienvenida" class="welcome-image">
                <div class="welcome-text">
                    <h4>Bienvenid@, </h4> <h4><?php echo $nombre; ?></h4>
                    <p>Al Sistema de Gestión </p>
                    <p>Administrativa</p>
                </div>
            </div>
        
        <div class="quick-access-container">
            <h2 class="quick-access-title">Accesos Rápidos</h2>
            <div class="quick-access-grid">
                <!-- Fila 1 -->
                <a href="?action=admin&method=inventario" class="quick-access-item">
                    <i class="fas fa-warehouse"></i>
                    <h3>Inventario</h3>
                </a>
                
                <a href="?action=admin&method=proyectos" class="quick-access-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Proyectos Mensuales</h3>

                <a href="?action=admin&method=configuracion" class="quick-access-item">
                    <i class="fas fa-sliders-h"></i>
                    <h3>Configuración</h3>
                </a>
                
            </div>
        </div> <br><br>
        <footer>
        <div class="footer-content">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/menu.js"></script>

</body>
</html>