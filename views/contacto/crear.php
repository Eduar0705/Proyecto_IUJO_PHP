<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?> - Nuevo Contacto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="assets/css/contacto2.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                    <button type="button" class="btn btn-cancel btn-action" id="btnCancelar">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save btn-action" id="btnGuardar">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Mostrar alerta de error si existe
        <?php if (isset($error)): ?>
            Swal.fire({
                title: 'Error',
                text: '<?= addslashes($error) ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Confirmación al guardar
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Crear nuevo contacto?',
                html: `Confirma que deseas agregar este contacto al sistema<br><br>
                       <div class="alert alert-info p-2 text-start">
                           <i class="fas fa-info-circle me-2"></i> Verifica que toda la información sea correcta antes de continuar
                       </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check me-1"></i> Confirmar',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'text-start'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar el formulario
                    this.submit();
                }
            });
        });

        // Confirmación al cancelar
        document.getElementById('btnCancelar').addEventListener('click', function() {
            Swal.fire({
                title: '¿Cancelar cambios?',
                text: "¿Estás seguro de que deseas cancelar? Los datos ingresados se perderán.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Continuar editando',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?action=admin&method=contacto';
                }
            });
        });

        // Mostrar alerta de éxito si existe
        <?php if(isset($success) && $success): ?>
            Swal.fire({
                title: '¡Contacto creado!',
                html: `<div class="text-center">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h3 class="mb-2">¡Contacto creado con éxito!</h3>
                            <p class="text-muted mb-3">El nuevo contacto ha sido agregado al sistema</p>
                        </div>`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-eye me-1"></i> Ver Contactos',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Cerrar',
                reverseButtons: true,
                focusConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?action=admin&method=contacto';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>