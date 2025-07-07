<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Contactos</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="assets/css/contacto.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/contacto2.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    ?>
    
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>

    <main class="main-content">
        <div class="container">
            <h2 class="table-title mb-4"><i class="fas fa-address-book me-2"></i> Gestión de Contactos</h2>
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <!-- Barra de búsqueda -->
                <div class="search-container flex-grow-1">
                    <form method="GET" class="search-form">
                        <input type="hidden" name="action" value="admin">
                        <input type="hidden" name="method" value="contacto">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control py-2" 
                                    placeholder="Buscar por nombre, email o cargo...">
                            <button type="submit" class="btn btn-outline-secondary px-3">
                                <i class="fas fa-search me-1"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Botón Nuevo Contacto -->
                <div class="action-buttons">
                    <a href="?action=admin&method=crearContacto" class="btn btn-primary px-3 py-2">
                        <i class="fa-solid fa-plus me-1"></i> Nuevo Contacto
                    </a>
                </div>
            </div>

            <!-- Tabla de contactos -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tuTabla">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Nombre</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Teléfono</th>
                                <th class="py-3">Cargo</th>
                                <th class="py-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($contactos)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="no-contacts">
                                            <i class="fas fa-user-slash"></i>
                                            <h4 class="mb-2">No se encontraron contactos</h4>
                                            <p class="text-muted mb-0">Parece que aún no tienes contactos registrados</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($contactos as $contacto): ?>
                                    <tr>
                                        <td class="py-3 fw-medium"><?= htmlspecialchars($contacto['nombre'] ?? '') ?></td>
                                        <td class="py-3"><?= htmlspecialchars($contacto['email'] ?? '') ?></td>
                                        <td class="py-3"><?= htmlspecialchars($contacto['telefono'] ?? '') ?></td>
                                        <td class="py-3"><?= htmlspecialchars($contacto['cargo'] ?? '') ?></td>
                                        <td class="py-3 text-end">
                                            <div class="d-flex gap-2 justify-content-end table-actions">
                                                <!-- Botón Editar - Azul -->
                                                <a href="?action=admin&method=actualizarContacto&id=<?= $contacto['id'] ?>" 
                                                    class="btn btn-outline-primary">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                                
                                                <!-- Botón Eliminar - Rojo -->
                                                <a href="#" 
                                                    class="btn btn-outline-danger btn-eliminar-contacto"
                                                    data-id="<?= $contacto['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($contacto['nombre'] ?? '') ?>">
                                                    <i class="fa-solid fa-trash me-1"></i> Eliminar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <footer class="mt-4">
                <div class="text-center text-muted">
                    <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
                </div>
            </footer>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/busqueda.js" defer></script>
    <script>
        // Mostrar alertas de éxito/error del servidor
        <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                title: '¡Éxito!',
                text: '<?= addslashes(urldecode($_GET['success'])) ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            Swal.fire({
                title: 'Error',
                text: '<?= addslashes(urldecode($_GET['error'])) ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Confirmación para eliminar contacto
        document.querySelectorAll('.btn-eliminar-contacto').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                
                Swal.fire({
                    title: '¿Eliminar contacto?',
                    html: `Estás a punto de eliminar al contacto: <strong>${nombre}</strong><br><br>
                           <div class="alert alert-danger p-2 text-start">
                               <i class="fas fa-exclamation-circle me-2"></i> Esta acción no se puede deshacer
                           </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Eliminar',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'text-start'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `?action=admin&method=eliminarContacto&id=${id}&eliminado=true`;
                    }
                });
            });
        });

        // Mostrar confirmación si se eliminó correctamente
        <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == 'true'): ?>
            Swal.fire({
                title: '¡Contacto eliminado!',
                text: 'El contacto ha sido eliminado correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Limpiar parámetro de URL
                const url = new URL(window.location.href);
                url.searchParams.delete('eliminado');
                window.history.replaceState({}, '', url);
            });
        <?php endif; ?>
    </script>
</body>
</html>