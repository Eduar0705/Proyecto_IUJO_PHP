<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <!-- Cambié el orden de los CSS para priorizar tus estilos -->

    <link rel="stylesheet" href="assets/css/config2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">

</head>
<body>
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>

 <main class="main-content">
        <div class="container">
            <h1 class="page-title">Solicitudes</h1>
            <p class="text-muted mb-4">Aquí puedes gestionar las solicitudes.</p>
            
            <!-- Botón con estilo personalizado -->
            <a href="?action=admin&method=solicitudesUsuario" class="btn btn-outline-primary">
                <i class="fas fa-user-plus me-2"></i>Solicitudes de registro usuarios
            </a>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Solicitudes Pendientes</h5>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Fecha de Solicitud</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Mark Johnson</td>
                                        <td class="text-muted">Solicitud de acceso al sistema</td>
                                        <td class="text-muted">
                                            <i class="bi bi-calendar3 me-2"></i>10/05/2023
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>Pendiente
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-action btn-view" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-action btn-edit" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-action btn-delete" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <footer class="footer-content">
        <div class="container">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    
    <script>
        // Efecto hover para los botones de acción
        $(document).ready(function() {
            $('.action-buttons .btn').hover(
                function() {
                    $(this).css('transform', 'scale(1.1)');
                },
                function() {
                    $(this).css('transform', 'scale(1)');
                }
            );
        });
    </script>
</body>
</html>