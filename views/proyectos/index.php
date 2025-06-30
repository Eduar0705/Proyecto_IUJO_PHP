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
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>
    <main class="main-content">
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-project-diagram"></i> <?= $title ?></h2>
    
    <!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars(urldecode($_GET['success'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars(urldecode($_GET['error'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Barra de búsqueda y nuevo proyecto -->
    <div class="d-flex justify-content-between mb-4">
        <form method="GET" class="d-flex">
            <input type="hidden" name="action" value="admin">
            <input type="hidden" name="method" value="proyectos">
            <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar proyectos..." 
                   value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
        
        <a href="?action=admin&method=gestionarProyecto" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Proyecto
        </a>
    </div>
    
    <!-- Gráfico de torta -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Distribución por Estado</h5>
                </div>
                <div class="card-body">
                    <canvas id="estadoChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Resumen estadístico -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estadisticas['por_estado'] as $estado): ?>
                                    <tr>
                                        <td><?= ucfirst(str_replace('_', ' ', $estado['estado'])) ?></td>
                                        <td><?= $estado['cantidad'] ?></td>
                                        <td><?= $estado['porcentaje'] ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de proyectos -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Proyectos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $proyecto): ?>
                            <tr>
                                <td><?= $proyecto['id'] ?></td>
                                <td><?= htmlspecialchars($proyecto['nombre']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $proyecto['estado'] == 'completado' ? 'success' : 
                                        ($proyecto['estado'] == 'en_progreso' ? 'warning' : 'info') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $proyecto['estado'])) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($proyecto['fecha_inicio'])) ?></td>
                                <td><?= $proyecto['fecha_fin'] ? date('d/m/Y', strtotime($proyecto['fecha_fin'])) : '-' ?></td>
                                <td>
                                    <a href="?action=admin&method=gestionarProyecto&id=<?= $proyecto['id'] ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=admin&method=proyectos&eliminar=<?= $proyecto['id'] ?>" 
                                       class="btn btn-sm btn-danger" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para el gráfico de torta
const estadoData = {
    labels: [<?= implode(',', array_map(function($e) { return "'".ucfirst(str_replace('_', ' ', $e['estado']))."'"; }, $estadisticas['por_estado'])) ?>],
    datasets: [{
        data: [<?= implode(',', array_column($estadisticas['por_estado'], 'cantidad')) ?>],
        backgroundColor: [
            '#4e73df', // Planificado - azul
            '#f6c23e', // En progreso - amarillo
            '#1cc88a', // Completado - verde
            '#e74a3b'  // Cancelado - rojo
        ],
        hoverBackgroundColor: [
            '#2e59d9',
            '#dda20a',
            '#17a673',
            '#be2617'
        ]
    }]
};

// Configuración del gráfico
const config = {
    type: 'doughnut',
    data: estadoData,
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
};

// Renderizar el gráfico
window.onload = function() {
    const ctx = document.getElementById('estadoChart').getContext('2d');
    new Chart(ctx, config);
};
</script>

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