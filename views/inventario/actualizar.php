<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/inventario2.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="centered-form">
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="form-group">
                    <label class="required-field">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" 
                           value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"><?= 
                        htmlspecialchars($producto['descripcion']) ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="required-field">Categoría</label>
                        <select name="cantegoria_id" class="form-select" required>
                            <option value="">Seleccione una categoría</option>
                           <?php 
                            $categorias->data_seek(0);
                            while($cat = $categorias->fetch_assoc()):
                            ?>
                                <option value="<?= $cat['id'] ?>" 
                                    <?= $cat['id'] == $producto['cantegoria_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="required-field">Unidad de Medida</label>
                        <input type="text" name="unidad_medida" class="form-control" 
                               value="<?= htmlspecialchars($producto['unidad_medida']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="required-field">Stock</label>
                        <input type="number" name="stock" class="form-control" min="0" 
                               value="<?= htmlspecialchars($producto['stock']) ?>" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-reset">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</body>
</html>