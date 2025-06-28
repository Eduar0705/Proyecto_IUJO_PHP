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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/user.css">
</head>
<body>
    <!-- Menú Superior -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <?php include 'views/layout/header_User.php'; ?>
    </nav>
    
    <!-- Contenido Principal -->
    <div class="container-fluid mt-4">
        <!-- Tarjeta de Bienvenida -->
        <div class="container">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-text">
                        <h2>Bienvenid@, <?=htmlspecialchars($nombre); ?></h2>
                        <p class="lead">Sistema de Gestión de Solicitudes</p>
                        <div class="mt-3">
                            <a href="?action=users&method=nuevaSolicitud" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-plus-circle me-2"></i>Nueva Solicitud
                            </a>
                        </div>
                    </div>
                    <img src="assets/img/inicio2.png" alt="Imagen de bienvenida" class="welcome-img d-none d-lg-block">
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <!-- Acciones Rápidas -->
            <h4 class="section-title"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h4>
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="card quick-action-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt"></i>
                            <h5 class="card-title mt-2">Nueva Solicitud</h5>
                            <p class="text-muted small">Crea una nueva solicitud de materiales, comida o proyecto</p>
                            <a href="?action=users&method=nuevaSolicitud" class="btn btn-primary btn-sm mt-2">Crear</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card quick-action-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-history"></i>
                            <h5 class="card-title mt-2">Mis Solicitudes</h5>
                            <p class="text-muted small">Revisa el historial y estado de tus solicitudes</p>
                            <a href="?action=usuario&method=historial" class="btn btn-primary btn-sm mt-2">Ver Historial</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card quick-action-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-download"></i>
                            <h5 class="card-title mt-2">Documentos</h5>
                            <p class="text-muted small">Accede a formatos y documentos importantes</p>
                            <a href="?action=usuario&method=documentos" class="btn btn-primary btn-sm mt-2">Explorar</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado de Solicitudes -->
            <h4 class="section-title"><i class="fas fa-chart-pie me-2"></i>Estado de Mis Solicitudes</h4>
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="card status-card status-pending h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-warning">Pendientes</h5>
                                    <h2 class="mb-0"><?= $solicitudesPendientes ?? '0' ?></h2>
                                    <small class="text-muted">En revisión</small>
                                </div>
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card status-card status-approved h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-success">Aprobadas</h5>
                                    <h2 class="mb-0"><?= $solicitudesAprobadas ?? '0' ?></h2>
                                    <small class="text-muted">Completadas</small>
                                </div>
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card status-card status-rejected h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-danger">Rechazadas</h5>
                                    <h2 class="mb-0"><?= $solicitudesRechazadas ?? '0' ?></h2>
                                    <small class="text-muted">No aprobadas</small>
                                </div>
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas Solicitudes -->
            <div class="card shadow-sm mb-5 border-0">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="section-title mb-0"><i class="fas fa-list me-2"></i>Últimas Solicitudes</h4>
                        <a href="?action=usuario&method=historial" class="btn btn-sm btn-primary">Ver Todas</a>
                    </div>
                </div>
                <div class="card-body px-0">
                    <?php if (!empty($ultimasSolicitudes)): ?>
                        <?php foreach ($ultimasSolicitudes as $solicitud): ?>
                            <div class="request-item request-<?= strtolower($solicitud['estado']) ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 fw-bold"><?= htmlspecialchars($solicitud['tipo']) ?>: <?= htmlspecialchars($solicitud['subtipo']) ?></h5>
                                        <div class="d-flex align-items-center mt-2">
                                            <small class="text-muted me-3"><i class="far fa-calendar me-1"></i><?= date('d/m/Y', strtotime($solicitud['fecha_creacion'])) ?></small>
                                            <?php if(isset($solicitud['fecha_limite'])): ?>
                                                <small class="text-muted"><i class="far fa-clock me-1"></i>Límite: <?= date('d/m/Y', strtotime($solicitud['fecha_limite'])) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= 
                                            $solicitud['estado'] == 'Aprobada' ? 'success' : 
                                            ($solicitud['estado'] == 'Rechazada' ? 'danger' : 'warning') ?>">
                                            <?= $solicitud['estado'] ?>
                                        </span>
                                        <a href="?action=usuario&method=verSolicitud&id=<?= $solicitud['id'] ?>" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">No hay solicitudes recientes</p>
                            <a href="?action=users&method=nuevaSolicitud" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>Crear primera solicitud
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <p class="text-center text-muted mb-0">&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Activar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>