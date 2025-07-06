<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Contactos</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="assets/css/contacto.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
    <?php 
        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
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
                            <input type="text" name="busqueda" class="form-control py-2" 
                                   placeholder="Buscar por nombre, email o cargo..." 
                                   value="<?= htmlspecialchars($busqueda) ?>">
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
                    <table class="table table-hover align-middle">
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
                                                    class="btn btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal"
                                                    data-contact-id="<?= $contacto['id'] ?>"
                                                    data-contact-name="<?= htmlspecialchars($contacto['nombre'] ?? '') ?>">
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
    
    <!-- Modal de Confirmación para Eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Eliminar Contacto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-trash-alt fa-2x text-danger"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">¿Eliminar este contacto?</h5>
                            <p class="mb-2">Estás a punto de eliminar al contacto: <strong id="contactName"></strong></p>
                            <div class="alert alert-danger p-2 mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i> Esta acción no se puede deshacer
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Configuración del modal de eliminación
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Botón que activó el modal
            const button = event.relatedTarget;
            
            // Extraer información de los atributos data-*
            const contactId = button.getAttribute('data-contact-id');
            const contactName = button.getAttribute('data-contact-name');
            
            // Actualizar el contenido del modal
            document.getElementById('contactName').textContent = contactName;
            
            // Configurar el enlace de eliminación
            const deleteLink = document.getElementById('confirmDelete');
            deleteLink.href = `?action=admin&method=eliminarContacto&id=${contactId}`;
        });
        
        
    </script>
</body>
</html>