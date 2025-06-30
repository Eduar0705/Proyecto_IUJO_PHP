<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?> - Editar Contacto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/contacto.css">
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
        include 'views/layout/header_Admin.php'; 
        ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>
    
    <main class="main-content">
        <div class="form-centered-container">
            <h2 class="form-title"><i class="fa-solid fa-user-pen"></i> Editar Contacto</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" class="centered-form" id="editForm">
                <input type="hidden" name="action" value="admin">  
                <input type="hidden" name="method" value="actualizarContacto">
                <input type="hidden" name="id" value="<?= $contacto['id'] ?>">
                
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($contacto['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($contacto['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($contacto['telefono']) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="cargo" class="form-control form-control-lg" 
                           value="<?= htmlspecialchars($contacto['cargo']) ?>">
                </div>
                
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="?action=admin&method=contacto" class="btn-action btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-save btn-action"  data-bs-toggle="modal" data-bs-target="#updateConfirmationModal">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <!-- Modal de Confirmación para Actualización -->
    <div class="modal fade" id="updateConfirmationModal" tabindex="-1" aria-labelledby="updateConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateConfirmationModalLabel">
                        <i class="fas fa-sync-alt me-2"></i> Confirmar Actualización
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-edit fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">¿Actualizar información del contacto?</h5>
                            <p class="mb-0">Estás a punto de actualizar la información de: <strong><?= htmlspecialchars($contacto['nombre']) ?></strong></p>
                        </div>
                    </div>
                    <div class="alert alert-info p-3 mb-0">
                        <i class="fas fa-info-circle me-2"></i> Verifica que todos los datos sean correctos antes de continuar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmUpdate">
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
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle me-2"></i> Actualización Exitosa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h3 class="mb-2">¡Contacto actualizado!</h3>
                        <p class="text-muted mb-0">La información se ha guardado correctamente.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cerrar
                        </button>
                        <a href="?action=admin&method=contacto" class="btn btn-success">
                            <i class="fas fa-list me-1"></i> Ver Contactos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Confirmar actualización del contacto
        document.getElementById('confirmUpdate').addEventListener('click', function() {
            // Cerrar el modal de confirmación
            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('updateConfirmationModal'));
            confirmationModal.hide();
            
            // Envía el formulario
            document.getElementById('editForm').submit();
            
            // Mostrar modal de éxito (simulación)
            // En un caso real, esto debería mostrarse después de una respuesta exitosa del servidor
            setTimeout(function() {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            }, 800);
        });
        
        // Mostrar modal de éxito si la actualización fue exitosa
        <?php if(isset($success) && $success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        <?php endif; ?>
    </script>
</body>
</html>