<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Editar Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/inventario.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/contacto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>

    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>

    <main class="main-content">
        <div class="form-centered-container">
            <div class="form-header">
                <h1 class="form-title">
                    <i class="fas fa-edit me-2"></i> Editar Producto
                </h1>
                <a href="?action=admin&method=inventario" class="btn btn-cancel">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="centered-form">
                <form method="POST" action="" id="editProductForm">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="action" value="admin">
                    <input type="hidden" name="method" value="actualizar">

                    <div class="form-group mb-4">
                        <label class="required-field">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control form-control-lg"
                                value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?=
                            htmlspecialchars($producto['descripcion']) ?></textarea>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 form-group">
                            <label class="required-field">Categoría</label>
                            <select name="cantegoria_id" class="form-select" required>
                                <option value="">Seleccione una categoría</option>
                               <?php
                                $categorias = ($this->modelo->getDB())->query("SELECT * FROM categorias WHERE activo = 1");
                                while($cat = $categorias->fetch_assoc()): 
                                ?>
                                <option value="<?= $cat['id'] ?>" 
                                <?= (isset($producto) && $producto['cantegoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?> (<?= $cat['tipo'] ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="required-field">Unidad de Medida</label>
                            <input type="text" name="unidad_medida" class="form-control"
                                value="<?= htmlspecialchars($producto['unidad_medida']) ?>" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="required-field">Stock</label>
                            <input type="number" name="stock" class="form-control" min="0"
                                value="<?= htmlspecialchars($producto['stock']) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="?action=admin&method=inventario" class="btn-action btn-cancel">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-save btn-action" data-bs-toggle="modal" data-bs-target="#updateConfirmationModal">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Modal de Confirmación -->
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
                            <h5 class="mb-2">¿Actualizar información del producto?</h5>
                            <p class="mb-0">Estás a punto de actualizar el producto: <strong><?= htmlspecialchars($producto['nombre']) ?></strong></p>
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
                    <button type="button" class="btn btn-primary" id="confirmProductUpdate">
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
                        <h3 class="mb-2">¡Producto actualizado!</h3>
                        <p class="text-muted mb-0">La información se ha guardado correctamente.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cerrar
                        </button>
                        <a href="?action=admin&method=inventario" class="btn btn-success">
                            <i class="fas fa-list me-1"></i> Ver Inventario
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Cargar jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Confirmar actualización
        document.getElementById('confirmProductUpdate').addEventListener('click', function() {
            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('updateConfirmationModal'));
            confirmationModal.hide();
            document.getElementById('editProductForm').submit();
        });
        
        // Mostrar modal de éxito si existe
        <?php if(isset($success) && $success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('successModal')).show();
            });
        <?php endif; ?>
    </script>
</body>
</html>