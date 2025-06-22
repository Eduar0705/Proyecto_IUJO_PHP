<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/inventario2.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php 
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
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
    <div class="container mt-4">
        <center><h2 class="mb-4"><i class="fas fa-boxes"></i> Gestión de Inventario</h2></center>
        
        <!-- Barra de búsqueda y filtros -->
        <div class="form-search-container">
            <form method="GET" class="search-form">
                <div class="form-row">
                    <div class="form-field">
                        <input type="text" name="search" class="form-control" placeholder="Buscar productos..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    
                    <div class="form-field">
                        <select name="categoria_id" class="form-select">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>" <?= $categoria_id == $categoria['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categoria['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-button">
                        <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="crear.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </a>
            
            <div class="export-buttons">
                <a href="reportes.php?type=excel&search=<?= urlencode($search) ?>&categoria_id=<?= $categoria_id ?>" class="btn-excel">
                    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
                </a>
                <a href="exportar.php?type=pdf&search=<?= urlencode($search) ?>&categoria_id=<?= $categoria_id ?>" class="btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
                </a>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="table-container">
            <table class="data-table">
                <thead class="table-header-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Unidad</th>
                        <th>Stock</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No se encontraron productos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['id'] ?></td>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                                <td><?= htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') ?></td>
                                <td><?= htmlspecialchars($producto['unidad_medida']) ?></td>
                                <td><?= $producto['stock'] ?></td>
                                <td><?= date('d/m/Y', strtotime($producto['fecha_registro'])) ?></td>
                                <td class="table-actions">
                                    <a href="actualizar_inventario.php?id=<?= $producto['id'] ?>" class="action-link edit-action">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="eliminar_invitario.php?id=<?=$producto['id']?>" class="action-link delete-action" onclick="confirmarEliminacion(event, this.href)">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, url) {
            event.preventDefault(); // Evita que el enlace se ejecute directamente
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirige a eliminar_invitario.php
                }
            });
        }
    </script>
</body>
</html>