<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <!-- Cambié el orden de los CSS para priorizar tus estilos -->

    <link rel="stylesheet" href="assets/css/config2.css">
    <link rel="stylesheet" href="assets/css/menu.css">
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
                                        <th>Solicitante</th>
                                        <th>Titulo de solicitud</th>
                                        <th>Tipo</th>
                                        <th>Fecha de creacion</th>
                                        <th>Fecha limite</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($fila = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fila['id_informacion'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['solicitante'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['titulo'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['tipo'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['fecha_creacion'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars(($fila['fecha_inminente'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars(($fila['estado'] ?? '')); ?></td>
                                        <td>
                                            <a href="?action=admin&method=validarSolicitudProy&id=<?php echo $fila['id_informacion']; ?>" 
                                                class=" btn btn-success" onclick="confirmarAgregacion(event, this.href)">
                                                <i class="bi bi-check"></i>
                                            </a>
                                            <a href="?action=admin&method=eliminarSolicitudProy&id=<?php echo $fila['id_informacion']; ?>" 
                                                class=" btn btn-danger btn-sm" onclick="confirmarEliminacion(event, this.href)">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, url) {
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        function confirmarAgregacion(event, url) {
            event.preventDefault(); 
            
            Swal.fire({
                title: '¿Enviar la solicitud a presupuesto?',
                text: "¡Debes estar seguro de que vale la pena!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
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