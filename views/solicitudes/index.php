<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/solicitudes2.css">
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
            <h1 class="mt-4">Solicitudes</h1>
            <p>Aquí puedes gestionar las solicitudes.</p>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Solicitudes Pendientes</h5>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Fecha de Solicitud</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Mark Johnson</td>
                                        <td class="text-muted">Solicitud de acceso al sistema</td>
                                        <td class="text-muted">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            10/05/2023
                                        </td>
                                        <td>
                                            <span class="status-badge status-pending">
                                                <i class="bi bi-clock me-1"></i>
                                                Pendiente
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-view" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-edit" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-delete" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Sarah Williams</td>
                                        <td class="text-muted">Actualización de permisos</td>
                                        <td class="text-muted">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            12/05/2023
                                        </td>
                                        <td>
                                            <span class="status-badge" style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aprobado
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-view" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-edit" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-delete" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">David Chen</td>
                                        <td class="text-muted">Solicitud de vacaciones</td>
                                        <td class="text-muted">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            15/05/2023
                                        </td>
                                        <td>
                                            <span class="status-badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Rechazado
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="acciones-tabla">
                                            <button type="button" class="btn accion-ver" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn accion-editar" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn accion-eliminar" title="Eliminar">
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
        </div> <br><br><br>
        <footer>
        <div class="footer-content">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    </main>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</html>