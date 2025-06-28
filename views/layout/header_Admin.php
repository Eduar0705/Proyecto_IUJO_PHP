<!-- nav_admin.php corregido -->
<nav class="admin-nav">
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="logo-header">
        <!-- Ruta absoluta desde la raíz -->
        <a href="?action=admin">
            <img src="assets/img/Logo1.png" alt="INVILARA Logo">
        </a>
        <h1><?= APP_NAME ?> - Administrador</h1>
    </div>
    
    <div class="user-nav">
        <span class="welcome-msg"><?php echo "Bienvenido " . htmlspecialchars($nombre); ?></span>
        <div class="user-actions">
            <a href="?action=admin&method=registroDeUsuarios" class="btn-user-action">
                <i class="fas fa-user-plus"></i>
                <span>Registros</span>
            </a>
            <a href="?action=inicio" class="btn-user-action btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>