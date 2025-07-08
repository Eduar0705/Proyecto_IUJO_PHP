<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?: 'Inicio' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body>
    <!-- Menú Superior -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top sys-navbar">
        <?php include 'views/layout/header_User.php'; ?>
    </nav>
    
    <!-- Contenido Principal -->
    <div class="container-fluid">
        <!-- Tarjeta de Bienvenida -->
        <div class="container">
            <div class="sys-welcome">
                <div class="d-flex align-items-center justify-content-between sys-welcome__content">
                    <div class="sys-welcome__text">
                        <h2 class="sys-welcome__title">Bienvenid@, <?= htmlspecialchars($nombre); ?></h2>
                        <p class="sys-welcome__subtitle">Sistema de Gestión de Solicitudes</p>
                    </div>
                    <img src="assets/img/inicio2.png" alt="Imagen de bienvenida" class="sys-welcome__img">
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <h4 class="sys-section-title">
                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
            </h4>
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="sys-card sys-quick-action">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-alt sys-quick-action__icon"></i>
                            <h5 class="card-title mt-3 mb-2">Nueva Solicitud</h5>
                            <p class="text-muted small mb-3">Crea una nueva solicitud de materiales, comida o proyecto</p>
                            <a href="?action=users&method=nuevaSolicitud" class="sys-btn sys-btn--primary btn-sm">
                                Crear
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sys-card sys-quick-action">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-history sys-quick-action__icon"></i>
                            <h5 class="card-title mt-3 mb-2">Mis Solicitudes</h5>
                            <p class="text-muted small mb-3">Revisa el historial y estado de tus solicitudes</p>
                            <a href="?action=usuario&method=historial" class="sys-btn sys-btn--primary btn-sm">
                                Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado de Solicitudes -->
            <h4 class="sys-section-title">
                <i class="fas fa-chart-pie me-2"></i>Estado de Mis Solicitudes
            </h4>
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="sys-card sys-card--pending">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-warning mb-2">Pendientes</h5>
                                    <h2 class="mb-1 fw-bold"><?= $solicitudesPendientes ?? '0' ?></h2>
                                    <small class="text-muted">En revisión</small>
                                </div>
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sys-card sys-card--approved">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-success mb-2">Aprobadas</h5>
                                    <h2 class="mb-1 fw-bold"><?= $solicitudesAprobadas ?? '0' ?></h2>
                                    <small class="text-muted">Completadas</small>
                                </div>
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sys-card sys-card--rejected">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-danger mb-2">Rechazadas</h5>
                                    <h2 class="mb-1 fw-bold"><?= $solicitudesRechazadas ?? '0' ?></h2>
                                    <small class="text-muted">No aprobadas</small>
                                </div>
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="sys-footer">
        <div class="container">
            <p class="text-center text-muted mb-0">
                &copy; 2025 INVILARA. Todos los derechos reservados.
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/menu.js"></script>
    <script>
        // Activar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Animaciones suaves al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.sys-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
