<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
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
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-<?= $id ? 'edit' : 'plus-circle' ?>"></i> <?= $title ?></h2>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Datos del Proyecto</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="?action=admin&method=gestionarProyecto<?= $id ? '&id='.$id : '' ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nombre del Proyecto *</label>
                        <input type="text" name="nombre" class="form-control" required
                            value="<?= isset($proyecto['nombre']) ? htmlspecialchars($proyecto['nombre']) : '' ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-select" required>
                            <option value="planificado" <?= (isset($proyecto['estado']) && $proyecto['estado'] == 'planificado') ? 'selected' : '' ?>>Planificado</option>
                            <option value="en_progreso" <?= (isset($proyecto['estado']) && $proyecto['estado'] == 'en_progreso') ? 'selected' : '' ?>>En Progreso</option>
                            <option value="completado" <?= (isset($proyecto['estado']) && $proyecto['estado'] == 'completado') ? 'selected' : '' ?>>Completado</option>
                            <option value="cancelado" <?= (isset($proyecto['estado']) && $proyecto['estado'] == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" class="form-control" required
                            value="<?= isset($proyecto['fecha_inicio']) ? htmlspecialchars($proyecto['fecha_inicio']) : '' ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Finalización</label>
                        <input type="date" name="fecha_fin" class="form-control"
                                value="<?= isset($proyecto['fecha_fin']) ? htmlspecialchars($proyecto['fecha_fin']) : '' ?>">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?= isset($proyecto['descripcion']) ? htmlspecialchars($proyecto['descripcion']) : '' ?></textarea>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= $id ? 'Actualizar' : 'Guardar' ?>
                        </button>
                        <a href="?action=admin&method=proyectos" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</main>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/menu.js"></script>
</body>
</html>