    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .welcome-card {
            background: linear-gradient(rgba(247, 246, 246, 0.75), rgba(240, 239, 238, 0.67));
            border-radius: 10px;
            padding: 20px;
            margin: 100px 0 20px 0;
        }

        .welcome-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px; /* Controla la separación entre texto e imagen */
            max-width: 100%;
        }

        .welcome-text {
            flex: 1;
            text-align: center;
        }

        .welcome-text h2 {
            font-size: 4.0rem;
            margin-bottom: 5px;
            color: #333;
        }

        .welcome-text .lead {
            font-size: 1.1rem;
            color: #555;
        }

        .welcome-img {
            width: 400px;
            height: auto;
            object-fit: contain;
        }
        .quick-action-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .quick-action-card:hover {
            transform: translateY(-5px);
        }
        .status-card {
            border-left: 4px solid;
        }
        .status-pending {
            border-left-color: #ffc107;
        }
        .status-approved {
            border-left-color: #28a745;
        }
        .status-rejected {
            border-left-color: #dc3545;
        }
        .request-item {
            border-left: 3px solid;
            padding-left: 10px;
            margin-bottom: 10px;
        }
        .request-pending {
            border-left-color: #ffc107;
        }
        .request-approved {
            border-left-color: #28a745;
        }
        .request-rejected {
            border-left-color: #dc3545;
        }
    </style>
</head>
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
<body>
    <!-- Menú Superior -->
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
                        <a class="nav-link active" href="?action=usuario&method=dashboard">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="?action=Usuario&method=nuevaSolicitud">
                            <i class="fas fa-plus-circle me-1"></i> Nueva Solicitud
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="?action=usuario&method=historial">
                            <i class="fas fa-history me-1"></i> Historial
                        </a>
                    </li>
                </ul>

                <!-- Dropdown usuario con separación -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?action=usuario&method=perfil"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?action=usuario&method=logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>