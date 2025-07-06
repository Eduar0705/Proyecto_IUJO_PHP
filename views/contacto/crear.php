<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?> - Nuevo Contacto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="assets/css/contacto.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        $nombre = $_SESSION['nombre'];
    }
    ?>
    
    <header class="admin-header">
        <?php 
        $nombre = $_SESSION['nombre'];
        include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>
    
    <main class="main-content">
        <div class="form-centered-container">
            <h2 class="form-title"><i class="fa-solid fa-address-card"></i> Crear Nuevo Contacto</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" class="centered-form" id="contactForm">
                <input type="hidden" name="action" value="admin">  
                <input type="hidden" name="method" value="crearContacto">
                
                <div class="mb-4">
                    <label class="form-label fw-medium">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-lg" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">Teléfono</label>
                    <input type="text" name="telefono" class="form-control form-control-lg">
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">Cargo</label>
                    <input type="text" name="cargo" class="form-control form-control-lg">
                </div>
                
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="?action=admin&method=contacto" class="btn btn-cancel btn-action">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-save btn-action" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="confirmationModalLabel"><i class="fas fa-check-circle me-2 text-success"></i> Confirmar Creación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light p-3 rounded-circle me-3">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">¿Crear nuevo contacto?</h5>
                            <p class="mb-0 text-muted">Confirma que deseas agregar este contacto al sistema</p>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> Verifica que toda la información sea correcta antes de continuar
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmSave">
                        <i class="fas fa-check me-1"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i> Contacto Creado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h4 class="mb-2">¡Contacto creado con éxito!</h4>
                        <p class="text-muted">El nuevo contacto ha sido agregado al sistema</p>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-light text-dark" data-bs-dismiss="modal">
                            <i class="fas fa-eye me-1"></i> Ver Contactos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Confirmar creación del contacto
        document.getElementById('confirmSave').addEventListener('click', function() {
            // Cerrar el modal de confirmación
            var confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            confirmationModal.hide();
            
            // Envía el formulario
            document.getElementById('contactForm').submit();
            
            // Mostrar modal de éxito (simulación)
            // En un caso real, esto debería mostrarse después de una respuesta exitosa del servidor
            setTimeout(function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            }, 800);
        });
        
        // Simulación de creación exitosa - para propósitos de demostración
        <?php if(isset($success) && $success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        <?php endif; ?>
    </script>
</body>
</html>