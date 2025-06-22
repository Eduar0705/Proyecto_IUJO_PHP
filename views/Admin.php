<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
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
        <?php include 'layout/navAdmin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'layout/menuAdmin.php'; ?>
    </div>
        <main class="main-content">
        <div class="welcome-container">
            <div class="welcome-text">
                <h1>Bienvenido, <?php echo $nombre; ?></h1>
                <p>Sistema de Gestión Administrativa de <?= APP_NAME ?></p>
            </div>
            <img src="assets//img/inicio2.png" alt="Bienvenida" class="welcome-image">
        </div>
        
        <div class="quick-access-container">
            <h2 class="quick-access-title">Accesos Rápidos</h2>
            <div class="quick-access-grid">
                <!-- Fila 1 -->
                <a href="?action=inventario&method=home" class="quick-access-item">
                    <i class="fas fa-warehouse"></i>
                    <h3>Inventario</h3>
                </a>
                
                <a href="../php/registro.php" class="quick-access-item">
                    <i class="fas fa-users-cog"></i>
                    <h3>Usuarios</h3>
                </a>
                
                <a href="../proyectos.php" class="quick-access-item">
                    <i class="fas fa-project-diagram"></i>
                    <h3>Proyectos</h3>
                </a>
                
                <!-- Fila 2 -->
                <a href="../recursos.php" class="quick-access-item">
                    <i class="fas fa-utensils"></i>
                    <h3>Recursos</h3>
                </a>
                
                <a href="../evaluacion.php" class="quick-access-item">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Evaluación</h3>
                </a>
                
                <a href="../configuracion.php" class="quick-access-item">
                    <i class="fas fa-sliders-h"></i>
                    <h3>Configuración</h3>
                </a>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</html>