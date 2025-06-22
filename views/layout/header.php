<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? 'Inicio' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body class="<?= isset($hideCarousel) && $hideCarousel ? 'hide-carousel' : '' ?>">
    <!-- Barra de navegación elegante -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" 
     style="background-color: rgba(255, 255, 255, 0.5); 
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 2px solid #ff4d4d !important;
            transition: all 0.3s ease;">
    <div class="container">
            <a class="navbar-brand" href="?action=inicio">
                <img src="assets/img/Logo1.png" alt="Logo" width="180" class="d-inline-block align-top me-2">
    <!--  <span class="fw-bold fs-4 mt-1"><?= APP_NAME ?></span>-->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto" style="gap: 1.5rem;">
                    <li class="nav-item">
                        <a class="nav-link active" href="?action=inicio"><i class="bi bi-house-door me-1"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=projects"> <i class="bi bi-cone-striped me-1"></i>Proyectos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=inicio&method=about"><i class="bi bi-people me-1"></i>Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=inicio&method=contact"><i class="bi bi-envelope me-1"></i>Contacto</a>
                    </li>
                </ul>
                
                <!-- Sección de Login/Registro (parte superior derecha) -->
                <div class="d-flex align-items-center">
                    <?php if(!isset($_SESSION['user'])): ?>
                    <a href="?action=inicio&method=login" class="btn btn-outline-primary me-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login
        </a>

                <a href="?action=inicio&method=register" class="btn" 
                        style="
                        color: #E31837;
                        border: 1px solid #E31837;
                        background-color: transparent;
                        transition: all 0.3s;"
                        onmouseover="this.style.backgroundColor='#E31837'; this.style.color='white'"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#E31837'">
                    <i class="bi bi-person-plus me-1"></i>Registro
                </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i><?= $_SESSION['user']['name'] ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?action=dashboard"><i class="bi bi-speedometer2 me-1"></i>Panel</a></li>
                                <li><a class="dropdown-item" href="?action=profile"><i class="bi bi-person me-1"></i>Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?action=logout"><i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>


        