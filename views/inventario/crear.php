<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/inventario2.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $nombre = $_SESSION['nombre'];
}
?>
<body>
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
            <h2 class="form-title"><i class="fa-solid fa-plus"></i> Crear Nuevo Producto</h2>
            
            <form method="POST" class="centered-form" id="form-producto">
                <input type="hidden" name="action" value="admin">  
                <input type="hidden" name="method" value="crear">
                
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="cantegoria_id" class="form-select" required>
                        <option value="">Seleccione categoría...</option>
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
                
                <div class="form-group">
                    <label>Unidad de Medida</label>
                    <input type="text" name="unidad_medida" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" min="0" value="0" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-save" id="btn-guardar">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-cancel" id="btn-cancelar">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    document.getElementById('form-producto').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '¿Guardar producto?',
            text: "¿Estás seguro de que deseas guardar este nuevo producto?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
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
    document.getElementById('btn-cancelar').addEventListener('click', function() {
        Swal.fire({
            title: '¿Cancelar operación?',
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
    </script>
</body>
</html>