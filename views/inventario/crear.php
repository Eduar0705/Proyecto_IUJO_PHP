<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="../templates/inventario.css">
    <link rel="shortcut icon" href="../templates/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <header class="admin-header">
        <?php include __DIR__ . '/../php/inc/nav_admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
       <?php include __DIR__.'/../php/inc/menu_admin.php'; ?>
    </div>
    <main class="main-content">
    <div class="form-centered-container">
        <h2 class="form-title"><i class="fa-solid fa-plus"></i> Crear Nuevo Producto</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="centered-form">
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
                    $categorias = $conex->query("SELECT * FROM categorias WHERE activo = 1");
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
                <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                <a href="Index_inventario.php" class="btn btn-cancel"><i class="fa-solid fa-xmark"></i> Cancelar</a>
            </div>
        </form>
    </div>
</main>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
         <script src="../php/inc/menu.js"></script>
</body>
</html>