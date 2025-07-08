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
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                        <button type="button" class="btn-action btn-cancel" id="btnCancelar">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-save btn-action" id="btnGuardar">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Cargar jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego Bootstrap -->
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
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Actualizar producto?',
                html: `Estás a punto de actualizar el producto: <strong><?= htmlspecialchars($producto['nombre']) ?></strong><br><br>
                       <div class="alert alert-info p-2 text-start">
                           <i class="fas fa-info-circle me-2"></i> Verifica que todos los datos sean correctos antes de continuar.
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
                        setTimeout(() => {
                    window.location.href = '?action=admin&method=inventario';
                }, 1500);
                }
            });
        });

        // Confirmación al cancelar
        document.getElementById('btnCancelar').addEventListener('click', function() {
            Swal.fire({
                title: '¿Cancelar cambios?',
                text: "¿Estás seguro de que deseas cancelar? Los cambios no guardados se perderán.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Continuar editando',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?action=admin&method=inventario';
                }
            });
        });

        // Mostrar alerta de éxito si existe

    </script>
</body>
</html>