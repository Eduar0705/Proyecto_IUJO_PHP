<nav class="admin-nav">
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="logo-header">
        <img src="../templates/logo.jpg" alt="INVILARA Logo">
        <h1>INVILARA</h1>
    </div>
    
    <div class="user-nav">
        <span class="welcome-msg"><?php echo "Bienvenido " . htmlspecialchars($nombre); ?></span>
        <div class="user-actions">
            <a href="./registro.php" class="btn-user-action">
                <i class="fas fa-user-plus"></i>
                <span>Registros</span>
            </a>
            <a href="../index.php" class="btn-user-action btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>