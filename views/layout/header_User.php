
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre = "Invitado";
if (!empty($_SESSION['nombre']) && is_string($_SESSION['nombre'])) {
    $nombre = htmlspecialchars(trim($_SESSION['nombre']), ENT_QUOTES, 'UTF-8');
}
$es_users = isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == 2;
?>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" 
    style="background-color: rgb(255, 255, 255); 
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 2px solid #ff4d4d !important;
            transition: all 0.3s ease;">
    <div class="container-fluid">
        <!-- Logo y nombre a la izquierda -->
        <a class="navbar-brand d-flex align-items-center me-0" href="?action=users">
            <img src="assets/img/Logo1.png" alt="Logo" width="160" height="60" class="d-inline-block align-top me-2">
            <h5 class="mb-0 "><?= APP_NAME ?> - Usuario</h5>
        </a>

        <!-- Botón para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarRight">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Grupo de elementos a la derecha -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarRight">
            <div class="d-flex align-items-center gap-4"> <!-- gap-4 añade espacio entre elementos -->
                <!-- Opciones de navegación -->
                <ul class="navbar-nav">
                    <li class="nav-item mx-2"> <!-- mx-2 para separación horizontal -->
                        <a class="nav-link active" href="?action=users&method=home">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="?action=users&method=nuevaSolicitud">
                            <i class="fas fa-plus-circle me-1"></i> Nueva Solicitud
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="?action=users&method=historial">
                            <i class="fas fa-history me-1"></i> Historial
                        </a>
                    </li>
                </ul>

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?action=inicio"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>